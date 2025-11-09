<?php

namespace App\Controllers;

use App\Controllers\BaseController;

// TAMBAHKAN 'USE' STATEMENTS BARU INI UNTUK FUNGSI PENJUALAN
use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\KeuanganModel; // Pastikan ini ada
use App\Models\UserModel; // SAYA TAMBAHKAN UNTUK RIWAYAT
use CodeIgniter\I18n\Time;

// GANTI NAMA CLASS DARI 'Manajemen' (JIKA SEBELUMNYA) MENJADI 'Karyawan'
class Karyawan extends BaseController
{
    /**
     * Menampilkan dashboard yang sesuai untuk role karyawan
     */
    public function dashboard()
    {
        // Ambil role dan username dari session
        $role = session()->get('role');
        $username = session()->get('username');

        $data = [
            'username' => $username
            // 'title' akan di-set di dalam if-else
        ];

        // Tentukan view mana yang akan di-load berdasarkan role
        if ($role === 'penjualan') {
            $data['title'] = 'Dashboard Penjualan';
            return view('dashboard_staff/dashboard_penjualan', $data);
        } elseif ($role === 'keuangan') {
            $data['title'] = 'Dashboard Keuangan';
            return view('dashboard_staff/dashboard_keuangan', $data);
        } elseif ($role === 'inventaris') {
            $data['title'] = 'Dashboard Inventaris';
            return view('dashboard_staff/dashboard_inventaris', $data);
        } else {
            // Role 'manajemen' biasa atau role lain
            $data['title'] = 'Dashboard';
            // Kita gunakan view penjualan sebagai default sementara
            return view('dashboard_staff/dashboard_penjualan', $data);
        }
    }

    /**
     * Halaman default untuk /karyawan
     */
    public function index()
    {
        // Arahkan ke halaman input penjualan sebagai default
        return redirect()->to('/karyawan/input_penjualan');
    }

    /**
     * (Method ini dari file Manajemen.php lama Anda)
     */
    public function inventaris()
    {
        // Pastikan 'use App\Models\ProdukModel;' ada di atas
        $produkModel = new ProdukModel();
        $data = [
            'title'  => 'Dashboard Inventaris',
            'produk' => $produkModel->findAll()
        ];
        return view('dashboard_staff/dashboard_inventaris', $data);
    }

    /**
     * [PERBAIKAN] Mengarahkan ke halaman riwayat penjualan
     */
    public function penjualan()
    {
        // Ini adalah link dari sidebar. Kita arahkan ke method riwayat_penjualan()
        return redirect()->to('karyawan/riwayat_penjualan');
    }

    /**
     * (Method ini dari file Manajemen.php lama Anda)
     */
    public function keuangan()
    {
        // ...
        return view('dashboard_staff/dashboard_keuangan');
    }

    // ==========================================================
    // === METHOD UNTUK RIWAYAT PENJUALAN ===
    // ==========================================================

    /**
     * [BARU] Menampilkan halaman Riwayat Transaksi Penjualan
     */
    public function riwayat_penjualan()
    {
        $penjualanModel = new PenjualanModel();

        // [PERBAIKAN DI SINI]
        // Mengganti 'users' menjadi 'user' sesuai nama tabel di database Anda
        $data = [
            'title' => 'Riwayat Transaksi Penjualan',
            'penjualan' => $penjualanModel
                ->select('penjualan.*, user.username') // dari 'users.username'
                ->join('user', 'user.id_user = penjualan.id_user', 'left') // dari 'users'
                ->orderBy('penjualan.tanggal', 'DESC')
                ->orderBy('penjualan.id_penjualan', 'DESC')
                ->findAll()
        ];

        return view('penjualan/riwayat_penjualan', $data);
    }

    // ==========================================================
    // === METHOD UNTUK INPUT TRANSAKSI (POS) ===
    // ==========================================================

    /**
     * Menampilkan halaman Input Transaksi (POS)
     */
    public function input_penjualan()
    {
        $produkModel = new ProdukModel();

        $data = [
            'title'  => 'Input Transaksi Penjualan',
            'produk' => $produkModel->where('stok >', 0)->findAll() // Hanya tampilkan produk yg ada stok
        ];

        return view('penjualan/input_transaksi', $data);
    }

    /**
     * Menyimpan transaksi baru ke database
     */
    public function store_penjualan()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai Database Transaction

        try {
            // 1. Ambil Model
            $penjualanModel = new PenjualanModel();
            $detailModel = new DetailPenjualanModel();
            $produkModel = new ProdukModel();
            $keuanganModel = new KeuanganModel();

            // 2. Ambil data dari FORM POST
            $cartItems   = $this->request->getPost('cart_items');
            $totalHarga  = $this->request->getPost('total_belanja');
            $jumlahDP    = $this->request->getPost('jumlah_dp');
            $statusBayar = $this->request->getPost('status_bayar'); // ('lunas' / 'belum_lunas')
            $metodeBayar = $this->request->getPost('metode_pembayaran');

            // [PERBAIKAN BUG] Konversi status_bayar ke format ENUM ('Lunas' / 'Belum Lunas')
            $statusBayarEnum = ($statusBayar == 'lunas') ? 'Lunas' : 'Belum Lunas';

            // 3. Simpan data ke tabel 'penjualan'
            $dataPenjualan = [
                'tanggal'           => Time::now()->toDateString(),
                'total'             => $totalHarga,
                'status_pembayaran' => $statusBayarEnum, // Menggunakan variabel yg sudah diperbaiki
                'metode_pembayaran' => $metodeBayar,
                'jumlah_dp'         => ($statusBayarEnum == 'Lunas') ? 0 : (empty($jumlahDP) ? 0 : $jumlahDP),
                'id_user'           => session()->get('user_id') // Kolom ini 'id_user' sudah benar
            ];

            $penjualanModel->insert($dataPenjualan);
            $idPenjualanBaru = $penjualanModel->getInsertID();

            // 4. Proses Keranjang (Cart)
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

                // 5. Kurangi Stok Produk
                $produkModel->where('id_produk', $item['id'])
                    ->set('stok', 'stok - ' . $qty, false)
                    ->update();
            }

            // 6. Simpan ke tabel 'detail_penjualan'
            if (!empty($dataDetailBatch)) {
                $detailModel->insertBatch($dataDetailBatch);
            }

            // 7. Catat ke Keuangan
            $jumlah_dibayar = ($statusBayarEnum == 'Lunas') ? $totalHarga : (empty($jumlahDP) ? 0 : $jumlahDP);

            if ($jumlah_dibayar > 0) {
                $keuanganModel->insert([
                    'tanggal'     => Time::now()->toDateString(),
                    'keterangan'  => 'Penjualan #' . $idPenjualanBaru,
                    'pemasukan'   => $jumlah_dibayar,
                    'pengeluaran' => 0,
                    'tipe'        => ($statusBayarEnum == 'Lunas') ? 'Pemasukan' : 'DP'
                ]);
            }

            $db->transComplete(); // Selesaikan Transaction

            // [PERBAIKAN LOGIKA REDIRECT]
            if ($statusBayarEnum === 'Lunas') {
                return redirect()->to('/karyawan/dashboard')->with('success', 'Transaksi Lunas berhasil disimpan!');
            } else {
                return redirect()->to('/karyawan/input_penjualan')->with('success', 'Transaksi (Belum Lunas) berhasil disimpan!');
            }
        } catch (\Exception $e) {
            $db->transRollback(); // Batalkan semua jika ada error
            log_message('error', 'Error di store_penjualan: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            return redirect()->to('/karyawan/input_penjualan')->with('error', 'Error: ' . $e->getMessage());
        }
    }


    // ==========================================================
    // === METHOD UNTUK EDIT/UPDATE TRANSAKSI ===
    // ==========================================================

    /**
     * Menampilkan halaman untuk mengedit transaksi penjualan.
     */
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

    /**
     * Memproses update data transaksi penjualan.
     */
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

        // 2. Ambil data dari form
        $cart_items_json = $this->request->getPost('cart_items');
        $cart_items = json_decode($cart_items_json, true);
        $total_belanja = $this->request->getPost('total_belanja');
        $status_bayar_form = $this->request->getPost('status_bayar'); // ('lunas' / 'belum_lunas')
        $jumlah_dp_form = $this->request->getPost('jumlah_dp') ?? 0;
        $metode_bayar_form = $this->request->getPost('metode_pembayaran');

        // [PERBAIKAN BUG] Konversi status_bayar ke format ENUM
        $statusBayarEnum = ($status_bayar_form == 'lunas') ? 'Lunas' : 'Belum Lunas';

        $db->transStart();

        // 3. Kembalikan Stok Lama
        $old_details = $detailModel->where('id_penjualan', $id_penjualan)->findAll();
        foreach ($old_details as $item) {
            $produkModel->update($item['id_produk'], ['stok' => $item['qty']], true); // increment
        }

        // 4. Hapus detail penjualan lama
        $detailModel->where('id_penjualan', $id_penjualan)->delete();

        // 5. Simpan detail penjualan (keranjang) baru dan kurangi stok baru
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

            // Kurangi stok produk (stok baru)
            $produkModel->update($item['id'], ['stok' => $qty], true); // decrement
        }
        if (!empty($dataDetailBatch)) {
            $detailModel->insertBatch($dataDetailBatch);
        }

        // 6. Update data penjualan utama
        $penjualanModel->update($id_penjualan, [
            'total'             => $total_belanja,
            'status_pembayaran' => $statusBayarEnum, // Menggunakan var yg diperbaiki
            'metode_pembayaran' => $metode_bayar_form,
            'jumlah_dp'         => ($statusBayarEnum == 'Lunas') ? 0 : $jumlah_dp_form,
        ]);

        // 7. Update data Keuangan
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
                'tipe'        => ($statusBayarEnum == 'Lunas') ? 'Pemasukan' : 'DP'
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui transaksi.');
        }

        return redirect()->to('karyawan/riwayat_penjualan')->with('success', 'Transaksi berhasil diperbarui.');
    }
}
