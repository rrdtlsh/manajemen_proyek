<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\RestokModel;
use App\Models\SupplierModel;

class InventarisController extends BaseController
{
    public function dashboard()
    {
        $produkModel   = new \App\Models\ProdukModel();
        $restokModel   = new \App\Models\RestokModel();
        $supplierModel = new \App\Models\SupplierModel();

        // Ringkasan
        $jumlah_produk     = $produkModel->countAll();
        $total_stok        = $produkModel->selectSum('stok')->first()['stok'] ?? 0;
        $restok_menunggu   = $restokModel->where('status', 'Menunggu')->countAllResults();
        $restok_disetujui  = $restokModel->where('status', 'Disetujui')->countAllResults();

        // Stok 7 Hari Terakhir (buat grafik line)
        $stokPerHari = [
            'labels' => [],
            'values' => []
        ];

        $stok_total = $produkModel->selectSum('stok')->first()['stok'] ?? 0;

        // Untuk 7 hari, tampilkan nilai sama (stok total)
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));

            $stokPerHari['labels'][] = date('d M', strtotime($tanggal));
            $stokPerHari['values'][] = (int)$stok_total;
        }

        // Data terbaru untuk tabel
        $latest_produk = $produkModel->orderBy('id_produk', 'DESC')->limit(5)->find();
        $latest_restok = $restokModel->orderBy('id_restok', 'DESC')->limit(5)->find();
        $latest_supplier = $supplierModel->orderBy('id_supplier', 'DESC')->limit(5)->find();

        $data = [
            'title'            => 'Dashboard Inventaris',
            'jumlah_produk'    => $jumlah_produk,
            'total_stok'       => $total_stok,
            'restok_menunggu'  => $restok_menunggu,
            'restok_disetujui' => $restok_disetujui,
            'stok_labels'      => json_encode($stokPerHari['labels']),
            'stok_values'      => json_encode($stokPerHari['values']),
            'latest_produk'    => $latest_produk,
            'latest_restok'    => $latest_restok,
            'latest_supplier'  => $latest_supplier
        ];

        return view('dashboard_staff/dashboard_inventaris', $data);
    }


    /**
     * Menampilkan halaman utama inventaris (daftar produk)
     */
    public function index()
    {
        $produkModel   = new ProdukModel();
        $supplierModel = new \App\Models\SupplierModel();
        $kategoriModel = new \App\Models\KategoriModel();

        $data = [
            'title'      => 'Manajemen Inventaris',
            'produk' => $produkModel->orderBy('id_produk', 'ASC')->findAll(),
            'suppliers'  => $supplierModel->findAll(),
            'kategori'   => $kategoriModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('inventaris/input_inventaris', $data);
    }

    /**
     * Menyimpan data produk BARU dari modal
     */
    public function store_produk()
    {
        $produkModel = new \App\Models\ProdukModel();

        $rules = [
            'kode_produk'   => 'required|is_unique[produk.kode_produk]|alpha_numeric|max_length[10]',
            'nama_produk'   => 'required|max_length[50]',
            'id_kategori'   => 'required|numeric',
            'id_supplier'   => 'required|numeric',
            'harga'         => 'required|integer|greater_than_equal_to[0]|less_than[1000000000000]',
            'stok'          => 'required|integer|greater_than[0]|less_than[1000000000000]',
            'tanggal_masuk' => 'required|valid_date',
        ];

        // 2. OPSI TAMBAHAN: Pesan Error Kustom (Agar bahasa Indonesia)
        $errors = [
            'kode_produk' => [
                'required'      => 'Kode produk wajib diisi.',
                'is_unique'     => 'Gagal! Kode produk ini sudah terdaftar.',
                'alpha_numeric' => 'Kode produk hanya boleh berisi huruf dan angka (tanpa spasi/simbol).',
                'max_length'    => 'Kode produk maksimal 10 karakter.'
            ],
            'nama_produk' => [
                'required'   => 'Nama produk wajib diisi.',
                'max_length' => 'Nama produk maksimal 50 karakter.'
            ],
            'harga' => [
                'required' => 'Harga wajib diisi.',
                'integer'  => 'Harga harus berupa angka bulat (tanpa titik/koma).',
                'less_than' => 'Harga tidak boleh melebihi 1 Triliun.'
            ],
            'stok' => [
                'required'     => 'Stok wajib diisi.',
                'integer'      => 'Stok harus berupa angka bulat.',
                'greater_than' => 'Stok harus lebih dari 0.',
                'less_than'    => 'Stok tidak boleh melebihi 1 Milyar.'
            ]
        ];

        // Masukkan $errors ke dalam fungsi validate
        if (!$this->validate($rules, $errors)) {
            log_message('debug', 'VALIDATION FAILED: ' . json_encode($this->validator->getErrors()));

            // Mengirim error spesifik ke session agar bisa dipanggil di View
            // withInput() penting agar isian form tidak hilang semua saat reload
            return redirect()->to('/karyawan/inventaris')->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Bagian ke bawah ini sudah benar, tidak perlu diubah ---
        $img = $this->request->getFile('gambar_produk');
        $namaGambar = 'default.jpg';

        if ($img && $img->isValid() && !$img->hasMoved()) {
            $namaGambar = $img->getRandomName();
            $img->move('uploads/produk/', $namaGambar);
        }

        $data = [
            'kode_produk'   => $this->request->getPost('kode_produk'),
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'id_supplier'   => $this->request->getPost('id_supplier'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'gambar_produk' => $namaGambar,
        ];

        $produkModel->insert($data);
        session()->setFlashdata('success', 'Produk berhasil ditambahkan.');
        // Disarankan ganti return redirect()->to(...) dengan ini:
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    /**
     * Memperbarui data produk dari modal
     */
    public function update_produk($id_produk)
    {
        $produkModel = new ProdukModel();

        $rules = [
            'kode_produk'   => "required|alpha_numeric|max_length[10]|is_unique[produk.kode_produk,id_produk,{$id_produk}]",
            'nama_produk'   => 'required|max_length[50]',
            'id_kategori'   => 'required|numeric',
            'id_supplier'   => 'required|numeric',
            'harga'         => 'required|integer|greater_than_equal_to[0]|less_than[1000000000000]',
            'stok'          => 'required|integer|greater_than[0]|less_than[1000000000]',
            'tanggal_masuk' => 'required|valid_date',
            'gambar_produk' => [
                'label' => 'Foto Produk',
                'rules' => 'permit_empty|is_image[gambar_produk]'
                    . '|mime_in[gambar_produk,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[gambar_produk,2048]',
            ],
        ];

        $errors = [
            'kode_produk' => [
                'required'      => 'Kode produk wajib diisi.',
                'is_unique'     => 'Gagal! Kode produk ini sudah terdaftar.',
                'alpha_numeric' => 'Kode produk hanya boleh huruf dan angka.',
                'max_length'    => 'Kode produk maksimal 10 karakter.'
            ],
            'nama_produk' => [
                'required'   => 'Nama produk wajib diisi.',
                'max_length' => 'Nama produk maksimal 50 karakter.'
            ],
            'stok' => [
                'required'     => 'Stok wajib diisi.',
                'integer'      => 'Stok harus berupa angka bulat.',
                'greater_than' => 'Stok harus lebih dari 0.',
                'less_than'    => 'Stok tidak boleh melebihi 1 Milyar.' // Pesan Error Baru
            ],
            'harga' => [
                'integer' => 'Harga harus angka bulat.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            // Mengirim pesan error spesifik pertama yang ditemukan
            $validationErrors = $this->validator->getErrors();
            $firstError = reset($validationErrors);
            return redirect()->to('karyawan/inventaris')->withInput()->with('error', 'Gagal Update: ' . $firstError);
        }

        // --- Proses update database ke bawah tetap sama ---
        $produkLama = $produkModel->find($id_produk);
        if (!$produkLama) {
            return redirect()->to('/karyawan/inventaris')->with('error', 'Produk tidak ditemukan.');
        }

        $namaGambar = $produkLama['gambar_produk'];
        $img = $this->request->getFile('gambar_produk');

        if ($img && $img->isValid() && !$img->hasMoved()) {
            if ($namaGambar != 'default.jpg' && file_exists('uploads/produk/' . $namaGambar)) {
                unlink('uploads/produk/' . $namaGambar);
            }
            $namaGambar = $img->getRandomName();
            $img->move('uploads/produk/', $namaGambar);
        }

        $data = [
            'kode_produk'   => $this->request->getPost('kode_produk'),
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'id_supplier'   => $this->request->getPost('id_supplier'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'gambar_produk' => $namaGambar,
        ];

        $produkModel->update($id_produk, $data);
        return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus data produk
     */
    public function delete_produk($id_produk)
    {
        $produkModel = new ProdukModel();
        $produk = $produkModel->find($id_produk);

        if ($produk) {
            if ($produk['gambar_produk'] != 'default.jpg') {
                $imagePath = 'uploads/produk/' . $produk['gambar_produk'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $produkModel->delete($id_produk);
            return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil dihapus.');
        } else {
            return redirect()->to('/karyawan/inventaris')->with('error', 'Produk gagal dihapus atau tidak ditemukan.');
        }
    }

    /**
     * Menampilkan detail produk
     */
    public function detail_produk($id_produk)
    {
        $produkModel   = new \App\Models\ProdukModel();
        $supplierModel = new \App\Models\SupplierModel();
        $kategoriModel = new \App\Models\KategoriModel();

        $produk = $produkModel->find($id_produk);
        if (!$produk) {
            return redirect()->to('/karyawan/inventaris')->with('error', 'Produk tidak ditemukan.');
        }

        $kategori = $kategoriModel->find($produk['id_kategori']);
        $supplier = $supplierModel->find($produk['id_supplier']);

        $data = [
            'title'     => 'Detail Produk',
            'produk'    => $produk,
            'kategori'  => $kategori,
            'supplier'  => $supplier,
        ];

        return view('inventaris/detail_produk', $data);
    }

    /**
     * Menampilkan halaman restok supplier
     */
    public function restok_supplier()
    {
        $restokModel   = new \App\Models\RestokModel();
        $supplierModel = new \App\Models\SupplierModel();

        $data = [
            'title'       => 'Restok Barang Supplier',
            'data_restok' => $restokModel->orderBy('id_restok', 'ASC')->findAll(),
            'suppliers'   => $supplierModel->orderBy('nama_supplier', 'ASC')->findAll() // 🔹 tambahan penting
        ];

        return view('inventaris/restok_supplier', $data);
    }


    /**
     * Menyimpan/Update data restok dari modal
     */
    /**
     * Menyimpan/Update data restok dari modal (SUDAH DIPERBAIKI)
     */
    public function store_restok()
    {
        $restokModel = new RestokModel();

        // 1. TAMBAHKAN VALIDASI DI SINI
        // Agar nama barang maksimal 50 huruf dan qty maksimal 9999
        $rules = [
            'nama_supplier' => 'required',
            'nama_barang'   => [
                // Regex ini melarang huruf yang sama diulang 5x berturut-turut (misal: ppppp)
                'rules'  => 'required|max_length[50]|regex_match[/^(?!.*(.)\1{4}).*$/]',
                'errors' => [
                    'max_length'  => 'Nama barang maksimal 50 karakter.',
                    'regex_match' => 'Nama barang tidak valid (spam karakter).'
                ]
            ],
            'qty' => [
                'rules'  => 'required|integer|less_than_equal_to[9999]',
                'errors' => [
                    'less_than_equal_to' => 'Maksimal jumlah barang adalah 9.999 per transaksi.'
                ]
            ],
            'harga_satuan'  => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan error ke modal
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. HITUNG TOTAL HARGA DI BACKEND (LEBIH AMAN)
        // Jangan ambil 'total_harga' dari post input, karena user bisa memanipulasi HTML
        $qty   = $this->request->getPost('qty');
        $harga = $this->request->getPost('harga_satuan');
        $total_fix = $qty * $harga;

        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'qty'           => $qty,
            'harga_satuan'  => $harga,
            'total_harga'   => $total_fix, // Gunakan hasil hitungan server

            // status inventaris ke owner
            'status'        => 'Menunggu',
            'status_owner'  => 'Menunggu',
        ];

        $id_restok = $this->request->getPost('id_restok');

        if (empty($id_restok)) {
            // INSERT baru
            $restokModel->insert($data);
            $message = 'Permintaan restok berhasil dibuat (Menunggu persetujuan owner).';
        } else {
            // UPDATE
            $restokModel->update($id_restok, $data);
            $message = 'Data restok berhasil diperbarui.';
        }

        return redirect()->to('/karyawan/inventaris/restok')->with('success', $message);
    }

    public function detail_restok($id_restok)
    {
        $restokModel = new RestokModel();
        $supplierModel = new SupplierModel();

        $restok = $restokModel->find($id_restok);

        if (!$restok) {
            return redirect()->to('/karyawan/inventaris/restok')->with('error', 'Data restok tidak ditemukan.');
        }

        $supplier = $supplierModel->where('nama_supplier', $restok['nama_supplier'])->first();

        $data = [
            'title'    => 'Detail Restok Supplier',
            'restok'   => $restok,
            'supplier' => $supplier,
        ];

        return view('inventaris/detail_restok', $data);
    }

    /**
     * Menghapus data restok
     */
    public function delete_restok($id_restok)
    {
        $restokModel = new RestokModel();
        $restok = $restokModel->find($id_restok);

        if ($restok) {
            $restokModel->delete($id_restok);
            return redirect()->to('/karyawan/inventaris/restok')->with('success', 'Data restok berhasil dihapus.');
        } else {
            return redirect()->to('/karyawan/inventaris/restok')->with('error', 'Data tidak ditemukan.');
        }
    }

    /**
     * Halaman daftar supplier
     */
    public function supplier()
    {
        $supplierModel = new \App\Models\SupplierModel();
        $data = [
            'title'     => 'Data Supplier',
            'suppliers' => $supplierModel->orderBy('id_supplier', 'ASC')->findAll()
        ];
        return view('inventaris/supplier', $data);
    }

    public function store_supplier()
    {
        $supplierModel = new \App\Models\SupplierModel();
        // $validation = \Config\Services::validation(); // Baris ini sebenarnya tidak wajib jika menggunakan $this->validate

        // 1. Definisi Rules
        $rules = [
            'nama_supplier' => 'required|is_unique[supplier.nama_supplier]',
            'alamat'        => 'required',
            'no_telp'       => 'required|is_natural|exact_length[12]'
        ];

        // 2. Definisi Pesan Error Custom (Bahasa Indonesia)
        $errors = [
            'nama_supplier' => [
                'required'  => 'Nama Supplier wajib diisi.',
                'is_unique' => 'Nama Supplier ini sudah terdaftar.'
            ]
        ];

        // 3. Masukkan $errors sebagai parameter kedua di fungsi validate()
        if (!$this->validate($rules, $errors)) {
            $validation = \Config\Services::validation();

            // Mengambil error dan menjadikannya flashdata
            session()->setFlashdata('error', implode('<br>', $validation->getErrors()));
            return redirect()->back()->withInput();
        }

        $supplierModel->save([
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_telp'       => $this->request->getPost('no_telp'),
        ]);

        session()->setFlashdata('success', 'Supplier berhasil ditambahkan.');
        return redirect()->to('karyawan/inventaris/supplier');
    }

    public function update_supplier($id_supplier)
    {
        $supplierModel = new \App\Models\SupplierModel();
        $supplierModel->update($id_supplier, [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_telp'       => $this->request->getPost('no_telp'),
        ]);

        session()->setFlashdata('success', 'Data supplier berhasil diperbarui.');
        return redirect()->to('karyawan/inventaris/supplier');
    }

    public function delete_supplier($id_supplier)
    {
        $supplierModel = new SupplierModel();

        if (!$supplierModel->find($id_supplier)) {
            return redirect()->back()->with('error', 'Data supplier tidak ditemukan.');
        }

        $supplierModel->delete($id_supplier);

        //kembali ke halaman data supplier (bukan kembali ke detail)
        return redirect()->to('karyawan/inventaris/supplier')->with('success', 'Supplier berhasil dihapus.');
    }


    public function detail_supplier($id_supplier)
    {
        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->find($id_supplier);

        if (!$supplier) {
            return redirect()->to('/karyawan/inventaris')->with('error', 'Supplier tidak ditemukan.');
        }

        $data = [
            'title'    => 'Detail Supplier',
            'supplier' => $supplier
        ];

        return view('inventaris/detail_supplier', $data);
    }

    public function cek_kode_otomatis()
    {
        if ($this->request->isAJAX()) {
            $kode = $this->request->getVar('kode_produk');
            $produkModel = new \App\Models\ProdukModel();

            $data = $produkModel->where('kode_produk', $kode)->first();

            $response = [
                'status' => ($data) ? 'taken' : 'available',
                // Kirim token CSRF baru agar request berikutnya tidak Error 403
                'token' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }
    }
}
