<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\KeuanganModel;
use App\Models\PelangganModel;
use CodeIgniter\I18n\Time;

class PenjualanController extends BaseController
{
    /**
     * Dashboard khusus untuk role Penjualan.
     */
    public function dashboard()
    {
        $penjualanModel = new \App\Models\PenjualanModel();

        $data = [
            'username' => session()->get('username')
        ];

        $salesData = $penjualanModel->getDashboardData();
        $data = array_merge($data, $salesData);
        $data['title'] = 'Dashboard Penjualan';

        return view('dashboard_staff/dashboard_penjualan', $data);
    }

    public function index()
    {
        // [PERBAIKAN] Arahkan ke rute 'karyawan/'
        return redirect()->to('karyawan/input_penjualan');
    }

    public function riwayat_penjualan()
    {
        $penjualanModel = new PenjualanModel();

        $data = [
            'title' => 'Riwayat Transaksi Penjualan',
            'penjualan' => $penjualanModel
                ->select('penjualan.*, pelanggan.nama_pelanggan')
                ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
                ->orderBy('penjualan.tanggal', 'DESC')
                ->orderBy('penjualan.id_penjualan', 'DESC')
                ->findAll()
        ];

        return view('penjualan/riwayat_penjualan', $data);
    }

    public function input_penjualan()
    {
        $produkModel = new ProdukModel();
        $data = [
            'title'  => 'Input Transaksi Penjualan',
            'produk' => $produkModel->where('stok >', 0)->findAll()
        ];
        return view('penjualan/input_transaksi', $data);
    }

    public function store_penjualan()
    {
        $db = \Config\Database::connect();

        $statusBayar = $this->request->getPost('status_bayar');
        $jumlahDP    = $this->request->getPost('jumlah_dp');
        $cartItems   = $this->request->getPost('cart_items');
        $idPelanggan = $this->request->getPost('id_pelanggan');
        $tanggal     = $this->request->getPost('tanggal');

        $rules = [
            'tanggal'      => 'required|valid_date',
            'id_pelanggan' => 'required|numeric|greater_than[0]',
            'status_bayar' => 'required|in_list[lunas,belum_lunas]',
            'cart_items'   => 'required|min_length[3]',
        ];
        $messages = [
            'tanggal.required'      => 'Tanggal transaksi wajib diisi.',
            'id_pelanggan.required' => 'Pelanggan wajib dipilih.',
            'cart_items.min_length' => 'Keranjang belanja tidak boleh kosong.',
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }
        if ($statusBayar == 'belum_lunas' && (empty($jumlahDP) || !is_numeric($jumlahDP) || $jumlahDP <= 0)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah DP wajib diisi dan harus lebih dari 0 jika status Belum Lunas.');
        }
        if (empty($cartItems) || $cartItems == '[]') {
            return redirect()->back()->with('error', 'Keranjang belanja tidak boleh kosong.');
        }

        $db->transStart();

        try {
            $penjualanModel = new PenjualanModel();
            $detailModel = new DetailPenjualanModel();
            $produkModel = new ProdukModel();
            $keuanganModel = new KeuanganModel();

            $totalHarga    = $this->request->getPost('total_belanja');
            $metodeBayar = $this->request->getPost('metode_pembayaran');
            $statusBayarEnum = ($statusBayar == 'lunas') ? 'Lunas' : 'Belum Lunas';

            $dataPenjualan = [
                'tanggal'           => $tanggal,
                'total'             => $totalHarga,
                'status_pembayaran' => $statusBayarEnum,
                'metode_pembayaran' => $metodeBayar,
                'jumlah_dp'         => ($statusBayarEnum == 'Lunas') ? 0 : $jumlahDP,
                'id_user'           => session()->get('user_id'),
                'id_pelanggan'      => $idPelanggan,
            ];

            $penjualanModel->insert($dataPenjualan);
            $idPenjualanBaru = $penjualanModel->getInsertID();

            $items = json_decode($cartItems, true);
            $dataDetailBatch = [];

            foreach ($items as $item) {
                $qty = (int)($item['qty'] ?? 0);
                $subtotal = (float)($item['subtotal'] ?? 0);
                $harga_satuan = ($qty > 0) ? $subtotal / $qty : 0;

                $dataDetailBatch[] = [
                    'id_penjualan' => $idPenjualanBaru,
                    'id_produk'    => $item['id'],
                    'qty'          => $qty,
                    'harga_satuan' => $harga_satuan
                ];

                $produkModel->where('id_produk', $item['id'])
                    ->set('stok', 'stok - ' . $qty, false)
                    ->update();
            }

            if (!empty($dataDetailBatch)) {
                $detailModel->insertBatch($dataDetailBatch);
            }

            $jumlah_dibayar = ($statusBayarEnum == 'Lunas') ? $totalHarga : $jumlahDP;

            if ($jumlah_dibayar > 0) {
                $keuanganModel->insert([
                    'tanggal'     => $tanggal,
                    'keterangan'  => 'Penjualan #' . $idPenjualanBaru,
                    'pemasukan'   => $jumlah_dibayar,
                    'pengeluaran' => 0,
                    'tipe'        => ($statusBayarEnum == 'Lunas') ? 'Pemasukan' : 'DP',
                    'id_user'     => session()->get('user_id')
                ]);
            }

            $db->transComplete();

            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            if ($statusBayarEnum === 'Lunas') {
                return redirect()->to('/karyawan/input_penjualan')->with('success', 'Transaksi Lunas berhasil disimpan!');
            } else {
                return redirect()->to('/karyawan/input_penjualan')->with('success', 'Transaksi (Belum Lunas) berhasil disimpan!');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error di store_penjualan: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('/karyawan/input_penjualan')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit untuk satu transaksi.
     * @param int $id_penjualan ID dari penjualan (didapat dari URL)
     */
    public function edit_penjualan(int $id_penjualan)
    {
        $penjualanModel = new PenjualanModel();
        $detailModel = new DetailPenjualanModel();
        $produkModel = new ProdukModel();
        $pelangganModel = new PelangganModel();

        $penjualan = $penjualanModel->find($id_penjualan);

        if (!$penjualan) {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Transaksi tidak ditemukan.');
        }
        if ($penjualan['status_pembayaran'] == 'Lunas') {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Transaksi yang sudah lunas tidak dapat diedit.');
        }

        $produk = $produkModel->findAll();
        $detail_penjualan = $detailModel
            ->select('detail_penjualan.id_produk, detail_penjualan.qty, produk.nama_produk, produk.harga, produk.stok')
            ->join('produk', 'produk.id_produk = detail_penjualan.id_produk')
            ->where('detail_penjualan.id_penjualan', $id_penjualan)
            ->findAll();

        $cart_items_json = [];
        foreach ($detail_penjualan as $item) {
            $cart_items_json[] = [
                'id'    => $item['id_produk'],
                'nama'  => $item['nama_produk'],
                'harga' => (float) $item['harga'],
                'stok'  => (int) $item['stok'],
                'qty'   => (int) $item['qty'],
            ];
        }

        $pelanggan_terpilih = null;
        if ($penjualan['id_pelanggan']) {
            $pelanggan_terpilih = $pelangganModel->find($penjualan['id_pelanggan']);
        }

        $data = [
            'title'                 => 'Edit Transaksi Penjualan',
            'produk'                => $produk,
            'penjualan'             => $penjualan,
            'detail_penjualan_json' => json_encode($cart_items_json),
            'pelanggan_terpilih'    => $pelanggan_terpilih,
        ];

        return view('penjualan/edit_transaksi', $data);
    }

    public function update_penjualan($id_penjualan)
    {
        $db = \Config\Database::connect();
        $penjualanModel = new PenjualanModel();
        $detailModel = new DetailPenjualanModel();
        $produkModel = new ProdukModel();
        $keuanganModel = new KeuanganModel();

        $penjualan = $penjualanModel->find($id_penjualan);
        if (!$penjualan) {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Transaksi ini tidak dapat diperbarui.');
        }

        $cart_items_json = $this->request->getPost('cart_items');
        $cart_items = json_decode($cart_items_json, true);
        $total_belanja = $this->request->getPost('total');
        $jumlah_dp_form = $this->request->getPost('jumlah_dp') ?? 0;
        $metode_bayar_form = $this->request->getPost('metode_pembayaran');
        $tanggal_form = $this->request->getPost('tanggal');
        $id_pelanggan_form = $this->request->getPost('id_pelanggan');

        $statusBayarEnum = 'Belum Lunas';
        $jumlah_dibayar_sekarang = floatval($jumlah_dp_form);

        if ($jumlah_dibayar_sekarang >= floatval($total_belanja) && floatval($total_belanja) > 0) {
            $statusBayarEnum = 'Lunas';
            $jumlah_dibayar_sekarang = floatval($total_belanja);
        }

        if ($statusBayarEnum == 'Belum Lunas' && (empty($jumlah_dp_form) || $jumlah_dp_form <= 0)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah DP wajib diisi jika status Belum Lunas.');
        }
        if (empty($cart_items) || $cart_items == '[]') {
            return redirect()->back()->withInput()->with('error', 'Keranjang tidak boleh kosong.');
        }

        $db->transStart();

        $old_details = $detailModel->where('id_penjualan', $id_penjualan)->findAll();
        foreach ($old_details as $item) {
            $produkModel->tambahStok($item['id_produk'], $item['qty']);
        }

        $detailModel->where('id_penjualan', $id_penjualan)->delete();

        $dataDetailBatch = [];
        foreach ($cart_items as $item) {
            $qty = (int)($item['qty'] ?? 0);
            $subtotal = (float)($item['subtotal'] ?? 0);
            $harga_satuan = ($qty > 0) ? $subtotal / $qty : 0;

            $dataDetailBatch[] = [
                'id_penjualan' => $id_penjualan,
                'id_produk'    => $item['id'],
                'qty'          => $qty,
                'harga_satuan' => $harga_satuan,
            ];
            $produkModel->kurangiStok($item['id'], $qty);
        }
        if (!empty($dataDetailBatch)) {
            $detailModel->insertBatch($dataDetailBatch);
        }

        $penjualanModel->update($id_penjualan, [
            'tanggal'           => $tanggal_form,
            'id_pelanggan'      => $id_pelanggan_form,
            'total'             => $total_belanja,
            'status_pembayaran' => $statusBayarEnum,
            'metode_pembayaran' => $metode_bayar_form,
            'jumlah_dp'         => ($statusBayarEnum == 'Lunas') ? 0 : $jumlah_dp_form,
        ]);

        $keuanganRecord = $keuanganModel->where('keterangan', 'Penjualan #' . $id_penjualan)->first();

        if ($keuanganRecord) {
            $keuanganModel->update($keuanganRecord['id_keuangan'], [
                'pemasukan' => $jumlah_dibayar_sekarang,
                'tipe'      => ($statusBayarEnum == 'Lunas') ? 'Pemasukan' : 'DP'
            ]);
        } elseif ($jumlah_dibayar_sekarang > 0) {
            $keuanganModel->insert([
                'tanggal'     => $tanggal_form,
                'keterangan'  => 'Penjualan #' . $id_penjualan,
                'pemasukan'   => $jumlah_dibayar_sekarang,
                'pengeluaran' => 0,
                'tipe'        => ($statusBayarEnum == 'Lunas') ? 'Pemasukan' : 'DP',
                'id_user'     => session()->get('user_id')
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui transaksi.');
        }

        // [PERBAIKAN] Redirect ke rute 'karyawan/'
        return redirect()->to('karyawan/riwayat_penjualan')->with('success', 'Transaksi #' . $id_penjualan . ' berhasil diperbarui.');
    }

    public function detail_penjualan($id_penjualan)
    {
        $penjualanModel = new \App\Models\PenjualanModel();
        $detailModel = new \App\Models\DetailPenjualanModel();
        $data['title'] = 'Detail Transaksi';

        $data['penjualan'] = $penjualanModel->getPenjualanById($id_penjualan);
        $data['detail_items'] = $detailModel->getDetailItems($id_penjualan);

        if (empty($data['penjualan'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data penjualan tidak ditemukan: ' . $id_penjualan);
        }

        return view('penjualan/detail_penjualan', $data);
    }

    public function delete_penjualan($id_penjualan)
    {
        $penjualanModel = new PenjualanModel();
        $detailModel = new DetailPenjualanModel();
        $produkModel = new ProdukModel();
        $keuanganModel = new KeuanganModel();

        $penjualan = $penjualanModel->find($id_penjualan);
        if (!$penjualan) {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Transaksi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $old_details = $detailModel->where('id_penjualan', $id_penjualan)->findAll();

            foreach ($old_details as $item) {
                // [PERBAIKAN] Panggil fungsi tambahStok dari ProdukModel
                // Pastikan fungsi ini ada di ProdukModel Anda
                $produkModel->tambahStok($item['id_produk'], $item['qty']);
            }

            $detailModel->where('id_penjualan', $id_penjualan)->delete();
            $keuanganModel->where('keterangan', 'Penjualan #' . $id_penjualan)->delete();
            $penjualanModel->delete($id_penjualan);

            $db->transComplete();

            if ($db->transStatus() === false) {
                // [PERBAIKAN] Redirect ke rute 'karyawan/'
                return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Gagal menghapus data transaksi.');
            }
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/riwayat_penjualan')->with('success', 'Transaksi #' . $id_penjualan . ' berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[DELETE_PENJUALAN] ' . $e->getMessage());
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Terjadi kesalahan server saat menghapus transaksi.');
        }
    }

    // --- FUNGSI AJAX ---

    public function searchProduk()
    {
        $produkModel = new ProdukModel();
        $term = $this->request->getGet('term');

        if ($term) {
            $data = $produkModel
                ->like('nama_produk', $term)
                ->where('stok >', 0)
                ->findAll(10);
        } else {
            $data = $produkModel
                ->where('stok >', 0)
                ->findAll(10);
        }

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item['id_produk'],
                'text' => $item['nama_produk'] . ' (Stok: ' . $item['stok'] . ')',
                'data' => $item
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }

    public function searchPelanggan()
    {
        $pelangganModel = new PelangganModel();
        $term = $this->request->getGet('term');

        if ($term) {
            $data = $pelangganModel
                ->like('nama_pelanggan', $term)
                ->orLike('no_hp', $term)
                ->findAll(10);
        } else {
            $data = $pelangganModel->findAll(10);
        }

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item['id_pelanggan'],
                'text' => $item['nama_pelanggan'] . ' (' . $item['no_hp'] . ')',
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }

    public function addPelanggan()
    {
        // [PERBAIKAN] Aturan validasi diperketat
        $rules = [
            'nama_pelanggan' => [
                'label' => 'Nama Pelanggan',
                'rules' => 'required|min_length[3]|alpha_space', // Hanya huruf dan spasi
                'errors' => [
                    'alpha_space' => 'Nama pelanggan hanya boleh berisi huruf dan spasi.'
                ]
            ],
            'no_hp'          => [
                'label' => 'No. HP',
                'rules' => 'required|min_length[10]|max_length[15]|numeric', // Hanya angka
                'errors' => [
                    'numeric' => 'Nomor HP hanya boleh berisi angka.'
                ]
            ],
            'alamat'         => [
                'label' => 'Alamat',
                'rules' => 'required|min_length[5]', // Dibuat wajib diisi
                'errors' => [
                    'required' => 'Alamat wajib diisi.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $pelangganModel = new PelangganModel();

        $data = [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'alamat'         => $this->request->getPost('alamat'),
        ];

        $idPelangganBaru = $pelangganModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pelanggan baru berhasil ditambahkan!',
            'data' => [
                'id' => $idPelangganBaru,
                'text' => $data['nama_pelanggan'] . ' (' . $data['no_hp'] . ')'
            ]
        ]);
    }
}
