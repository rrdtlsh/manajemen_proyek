<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\KeuanganModel;
use App\Models\UserModel;
use App\Models\PelangganModel;
use CodeIgniter\I18n\Time;

class Karyawan extends BaseController
{
    public function dashboard()
    {
        $role = session()->get('role');
        $username = session()->get('username');

        $data = [
            'username' => $username
        ];

        if ($role === 'penjualan') {
            $data['title'] = 'Dashboard Penjualan';
            return view('dashboard_staff/dashboard_penjualan', $data);
        } elseif ($role === 'keuangan') {
            return redirect()->to('karyawan/keuangan/laporan');
        } elseif ($role === 'inventaris') {
            $data['title'] = 'Dashboard Inventaris';
            return view('dashboard_staff/dashboard_inventaris', $data);
        } else {
            $data['title'] = 'Dashboard';
            return view('dashboard_staff/dashboard_penjualan', $data);
        }
    }

    public function index()
    {
        return redirect()->to('/karyawan/input_penjualan');
    }

    public function inventaris()
    {
        $produkModel = new ProdukModel();

        $data = [
            'title'  => 'Input Inventaris',
            'produk' => $produkModel->findAll()
        ];

        return view('inventaris/input_inventaris', $data);
    }

    public function penjualan()
    {
        return redirect()->to('karyawan/riwayat_penjualan');
    }

    public function keuanganPemasukan()
    {
        $keuanganModel = new KeuanganModel();

        $data = [
            'title' => 'Laporan Pemasukan',
            'laporan' => $keuanganModel
                ->whereIn('tipe', ['Pemasukan', 'DP']) // Hanya ambil Pemasukan dan DP
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];

        return view('keuangan/pemasukan', $data);
    }

    public function keuanganPengeluaran()
    {
        $keuanganModel = new KeuanganModel();

        $data = [
            'title' => 'Laporan Pengeluaran',
            'laporan' => $keuanganModel
                ->where('tipe', 'Pengeluaran')
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];

        return view('keuangan/pengeluaran', $data);
    }

    public function laporanKeuangan()
    {
        $keuanganModel = new KeuanganModel();
        $penjualanModel = new PenjualanModel();
        $year = $this->request->getGet('year') ?? date('Y');
        $quarter = $this->request->getGet('quarter');

        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        if ($quarter) {
            if ($quarter == 'Q1') {
                $startDate = "$year-01-01";
                $endDate = "$year-03-31";
            } elseif ($quarter == 'Q2') {
                $startDate = "$year-04-01";
                $endDate = "$year-06-30";
            } elseif ($quarter == 'Q3') {
                $startDate = "$year-07-01";
                $endDate = "$year-09-30";
            } elseif ($quarter == 'Q4') {
                $startDate = "$year-10-01";
                $endDate = "$year-12-31";
            }
        }

        $keuanganBuilder = $keuanganModel->where('tanggal >=', $startDate)->where('tanggal <=', $endDate);

        $laporan = (clone $keuanganBuilder)->orderBy('tanggal', 'ASC')->findAll();

        $totalPemasukan = (clone $keuanganBuilder)->whereIn('tipe', ['Pemasukan', 'DP'])->selectSum('pemasukan')->first()['pemasukan'] ?? 0;
        $totalPengeluaran = (clone $keuanganBuilder)->where('tipe', 'Pengeluaran')->selectSum('pengeluaran')->first()['pengeluaran'] ?? 0;
        $labaRugi = $totalPemasukan - $totalPengeluaran;

        $penjualanBuilder = $penjualanModel->where('status_pembayaran', 'Lunas')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate);

        $totalTransaksi = (clone $penjualanBuilder)->countAllResults();
        $totalNilaiTransaksi = (clone $penjualanBuilder)->selectSum('total')->first()['total'] ?? 0;
        $avgTransaksi = ($totalTransaksi > 0) ? $totalNilaiTransaksi / $totalTransaksi : 0;

        $data = [
            'title'               => 'Laporan Keuangan',
            'laporan'             => $laporan,
            'total_pemasukan'     => $totalPemasukan,
            'total_pengeluaran'   => $totalPengeluaran,
            'laba_rugi'           => $labaRugi,
            'total_transaksi'     => $totalTransaksi,
            'avg_transaksi'       => $avgTransaksi,
            'selectedYear'        => $year,
            'selectedQuarter'     => $quarter,
        ];

        return view('keuangan/laporan', $data);
    }

    public function input_keuangan()
    {
        $data = [
            'title' => 'Input Manual Keuangan'
        ];
        return view('keuangan/input_keuangan', $data);
    }

    public function store_keuangan()
    {
        $keuanganModel = new KeuanganModel();

        $tipe = $this->request->getPost('tipe');
        $jumlah = $this->request->getPost('jumlah');

        if (empty($tipe) || empty($jumlah) || empty($this->request->getPost('tanggal'))) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi.');
        }

        $data = [
            'tanggal'     => $this->request->getPost('tanggal'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'tipe'        => $tipe,
            'pemasukan'   => ($tipe == 'Pemasukan') ? $jumlah : 0,
            'pengeluaran' => ($tipe == 'Pengeluaran') ? $jumlah : 0,
            'id_user'     => session()->get('user_id')
        ];

        $keuanganModel->insert($data);

        return redirect()->to('karyawan/keuangan/pengeluaran')->with('success', 'Data pengeluaran manual berhasil ditambahkan.');
    }

    // ==========================================================
    // === METHOD UNTUK RIWAYAT PENJUALAN ===
    // ==========================================================

    public function riwayat_penjualan()
    {
        $penjualanModel = new PenjualanModel();

        $data = [
            'title' => 'Riwayat Transaksi Penjualan',
            // [PERUBAHAN] Join ke tabel 'pelanggan' bukan 'user'
            'penjualan' => $penjualanModel
                ->select('penjualan.*, pelanggan.nama_pelanggan')
                ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left') // Ganti ke 'pelanggan'
                ->orderBy('penjualan.tanggal', 'DESC')
                ->orderBy('penjualan.id_penjualan', 'DESC')
                ->findAll()
        ];

        return view('penjualan/riwayat_penjualan', $data);
    }

    // ==========================================================
    // === METHOD UNTUK INPUT TRANSAKSI (POS) ===
    // ==========================================================

    public function input_penjualan()
    {

        $produkModel = new ProdukModel();

        $data = [
            'title'  => 'Input Transaksi Penjualan',
            'produk' => $produkModel->where('stok >', 0)->findAll() // Ini dikembalikan
        ];

        return view('penjualan/input_transaksi', $data);
    }

    /**
     * Menyimpan transaksi baru ke database
     */
    public function store_penjualan()
    {
        $db = \Config\Database::connect();

        // ==========================================================
        // === [TAHAP 1: VALIDASI BACKEND] ===
        // ==========================================================

        // 1. Ambil data mentah
        $statusBayar = $this->request->getPost('status_bayar');
        $jumlahDP    = $this->request->getPost('jumlah_dp');
        $cartItems   = $this->request->getPost('cart_items');
        $idPelanggan = $this->request->getPost('id_pelanggan');
        $tanggal     = $this->request->getPost('tanggal');

        // 2. Aturan Validasi
        $rules = [
            'tanggal'      => 'required|valid_date',
            'id_pelanggan' => 'required|numeric|greater_than[0]',
            'status_bayar' => 'required|in_list[lunas,belum_lunas]',
            'cart_items'   => 'required|min_length[3]', // [3] adalah '[]' (keranjang kosong)
        ];

        $messages = [
            'tanggal.required'      => 'Tanggal transaksi wajib diisi.',
            'id_pelanggan.required' => 'Pelanggan wajib dipilih.',
            'cart_items.min_length' => 'Keranjang belanja tidak boleh kosong.',
        ];

        // 3. Jalankan Validasi
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // 4. Validasi Khusus untuk Uang (DP)
        if ($statusBayar == 'belum_lunas' && (empty($jumlahDP) || !is_numeric($jumlahDP) || $jumlahDP <= 0)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah DP wajib diisi dan harus lebih dari 0 jika status Belum Lunas.');
        }

        // 5. Validasi Khusus Keranjang (lagi)
        if (empty($cartItems) || $cartItems == '[]') {
            return redirect()->back()->with('error', 'Keranjang belanja tidak boleh kosong.');
        }

        // ==========================================================
        // === Akhir Validasi, lanjut ke proses Simpan ===
        // ==========================================================

        $db->transStart();

        try {
            $penjualanModel = new PenjualanModel();
            $detailModel = new DetailPenjualanModel();
            $produkModel = new ProdukModel();
            $keuanganModel = new KeuanganModel();

            // Ambil data yang sudah divalidasi
            $totalHarga  = $this->request->getPost('total_belanja');
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

            if ($statusBayarEnum === 'Lunas') {
                // DIUBAH: Arahkan kembali ke input_penjualan, bukan dashboard
                return redirect()->to('/karyawan/input_penjualan')->with('success', 'Transaksi Lunas berhasil disimpan!');
            } else {
                return redirect()->to('/karyawan/input_penjualan')->with('success', 'Transaksi (Belum Lunas) berhasil disimpan!');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error di store_penjualan: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            return redirect()->to('/karyawan/input_penjualan')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // === METHOD UNTUK EDIT/UPDATE TRANSAKSI ===
    // ==========================================================

    public function edit_penjualan($id_penjualan)
    {
        $penjualanModel = new PenjualanModel();
        $detailModel = new DetailPenjualanModel();
        $produkModel = new ProdukModel();

        $penjualan = $penjualanModel->find($id_penjualan);

        if (!$penjualan) {
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($penjualan['status_pembayaran'] == 'Lunas') {
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

        $data = [
            'title'                 => 'Edit Transaksi Penjualan',
            'produk'                => $produk,
            'penjualan'             => $penjualan,
            'detail_penjualan_json' => json_encode($cart_items_json),
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
        if (!$penjualan || $penjualan['status_pembayaran'] == 'Lunas') {
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Transaksi ini tidak dapat diperbarui.');
        }

        $cart_items_json = $this->request->getPost('cart_items');
        $cart_items = json_decode($cart_items_json, true);
        $total_belanja = $this->request->getPost('total_belanja');
        $status_bayar_form = $this->request->getPost('status_bayar'); // ('lunas' / 'belum_lunas')
        $jumlah_dp_form = $this->request->getPost('jumlah_dp') ?? 0;
        $metode_bayar_form = $this->request->getPost('metode_pembayaran');

        $statusBayarEnum = ($status_bayar_form == 'lunas') ? 'Lunas' : 'Belum Lunas';

        $db->transStart();

        $old_details = $detailModel->where('id_penjualan', $id_penjualan)->findAll();
        foreach ($old_details as $item) {
            $produkModel->update($item['id_produk'], ['stok' => $item['qty']], true);
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

            $produkModel->update($item['id'], ['stok' => $qty], true);
        }
        if (!empty($dataDetailBatch)) {
            $detailModel->insertBatch($dataDetailBatch);
        }

        $penjualanModel->update($id_penjualan, [
            'total'             => $total_belanja,
            'status_pembayaran' => $statusBayarEnum,
            'metode_pembayaran' => $metode_bayar_form,
            'jumlah_dp'         => ($statusBayarEnum == 'Lunas') ? 0 : $jumlah_dp_form,
        ]);

        $jumlah_dibayar_sekarang = ($statusBayarEnum == 'Lunas') ? $total_belanja : $jumlah_dp_form;
        $keuanganRecord = $keuanganModel->where('keterangan', 'Penjualan #' . $id_penjualan)->first();

        if ($keuanganRecord) {
            $keuanganModel->update($keuanganRecord['id_keuangan'], [
                'pemasukan' => $jumlah_dibayar_sekarang,
                'tipe'      => ($statusBayarEnum == 'Lunas') ? 'Pemasukan' : 'DP'
            ]);
        } elseif ($jumlah_dibayar_sekarang > 0) {
            $keuanganModel->insert([
                'tanggal'     => Time::now()->toDateString(),
                'keterangan'  => 'Penjualan #' . $id_penjualan,
                'pemasukan'   => $jumlah_dibayar_sekarang,
                'pengeluaran' => 0,
                'tipe'        => ($statusBayarEnum == 'Lunas') ? 'Pemasukan' : 'DP',
                'id_user'     => session()->get('user_id') // [BUG FIX] Tambahkan id_user
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui transaksi.');
        }

        return redirect()->to('karyawan/riwayat_penjualan')->with('success', 'Transaksi berhasil diperbarui.');
    }



    public function tambah_produk()
    {
        $data = [
            'title' => 'Tambah Produk Baru'
        ];
        return view('inventaris/v_tambah_produk', $data);
    }


    /**
     * [DIUBAH] Menyimpan data produk BARU ke database (dengan file upload)
     */
    public function store_produk()
    {
        $produkModel = new ProdukModel();

        // 1. Validasi Input (Tambahkan validasi gambar)
        $rules = [
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'gambar_produk' => [
                'label' => 'Foto Produk',
                'rules' => 'is_image[gambar_produk]'
                    . '|mime_in[gambar_produk,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[gambar_produk,2048]', // Maks 2MB
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        // 2. Ambil File Gambar
        $img = $this->request->getFile('gambar_produk');
        $namaGambar = 'default.jpg'; // Nama default

        // 3. Cek apakah ada gambar yang di-upload
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // Generate nama random
            $namaGambar = $img->getRandomName();
            // Pindahkan file ke folder public/uploads/produk/
            $img->move('uploads/produk/', $namaGambar);
        }

        // 4. Siapkan data untuk disimpan ke database
        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'gambar_produk' => $namaGambar, // Simpan nama file-nya
        ];

        // 5. Simpan ke database
        $produkModel->insert($data);

        return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil ditambahkan.');
    }


    public function edit_produk($id_produk)
    {
        $produkModel = new ProdukModel();
        $produk = $produkModel->find($id_produk);

        if (!$produk) {
            return redirect()->to('/karyawan/inventaris')->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'  => 'Edit Produk',
            'produk' => $produk
        ];

        return view('inventaris/v_edit_produk', $data);
    }

    public function update_produk($id_produk)
    {
        $produkModel = new ProdukModel();

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok')
        ];

        if (empty($data['nama_produk']) || empty($data['harga']) || !isset($data['stok'])) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi.');
        }

        $produkModel->update($id_produk, $data);

        return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete_produk($id_produk)
    {
        $produkModel = new ProdukModel();

        // Cek apakah produk ada
        $produk = $produkModel->find($id_produk);
        if ($produk) {
            $produkModel->delete($id_produk);
            return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil dihapus.');
        } else {
            return redirect()->to('/karyawan/inventaris')->with('error', 'Produk gagal dihapus atau tidak ditemukan.');
        }
    }


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
        $rules = [
            'nama_pelanggan' => 'required|min_length[3]',
            'no_hp'          => 'required|min_length[10]|max_length[15]',
            'alamat'         => 'permit_empty|min_length[5]',
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

    /**
     * [BARU] Menghapus data transaksi (penjualan, detail, keuangan)
     * dan mengembalikan stok produk.
     */
    public function delete_penjualan($id_penjualan)
    {
        $penjualanModel = new PenjualanModel();
        $detailModel = new DetailPenjualanModel();
        $produkModel = new ProdukModel();
        $keuanganModel = new KeuanganModel();

        // 1. Cek apakah transaksi ada
        $penjualan = $penjualanModel->find($id_penjualan);
        if (!$penjualan) {
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Transaksi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Mulai Transaksi

        try {
            // 2. Ambil detail barang yang terjual
            $old_details = $detailModel->where('id_penjualan', $id_penjualan)->findAll();

            // 3. Kembalikan Stok (Increment)
            foreach ($old_details as $item) {
                $produkModel->update($item['id_produk'], ['stok' => $item['qty']], true); // 'true' untuk increment
            }

            // 4. Hapus data di detail_penjualan
            $detailModel->where('id_penjualan', $id_penjualan)->delete();

            // 5. Hapus data di keuangan
            $keuanganModel->where('keterangan', 'Penjualan #' . $id_penjualan)->delete();

            // 6. Hapus data di penjualan (tabel utama)
            $penjualanModel->delete($id_penjualan);

            $db->transComplete(); // Selesaikan Transaksi

            if ($db->transStatus() === false) {
                return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Gagal menghapus data transaksi.');
            }

            return redirect()->to('karyawan/riwayat_penjualan')->with('success', 'Transaksi #' . $id_penjualan . ' berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[DELETE_PENJUALAN] ' . $e->getMessage());
            return redirect()->to('karyawan/riwayat_penjualan')->with('error', 'Terjadi kesalahan server saat menghapus transaksi.');
        }
    }
}
