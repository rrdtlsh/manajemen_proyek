<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\RestokModel;

class Inventaris extends BaseController
{
    /**
     * Menampilkan halaman utama inventaris (daftar produk)
     */
    public function index()
    {
        $produkModel = new ProdukModel();
        $data = [
            'title'  => 'Daftar Inventaris Produk', // Judul bisa disesuaikan
            'produk' => $produkModel->orderBy('id_produk', 'DESC')->findAll()
        ];
        return view('inventaris/input_inventaris', $data);
    }

    /**
     * Menampilkan halaman form tambah produk baru
     */
    public function tambah_produk()
    {
        $data = [
            'title' => 'Tambah Produk Baru',
            'validation' => \Config\Services::validation() // Untuk menampilkan error validasi
        ];
        return view('inventaris/v_tambah_produk', $data); // Pastikan view ini ada
    }

    /**
     * Menyimpan data produk baru ke database (dengan file upload)
     */
    public function store_produk()
    {
        $produkModel = new ProdukModel();

        // 1. Validasi Input
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
            // Kirimkan error validasi kembali ke form
            return redirect()->to('/inventaris/tambah_produk')->withInput();
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
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'gambar_produk' => $namaGambar, // Simpan nama file-nya
        ];

        // 5. Simpan ke database
        $produkModel->insert($data);

        // [PERBAIKAN] Redirect ke /inventaris/index
        return redirect()->to('/inventaris/index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman form edit produk
     */
    public function edit_produk($id_produk)
    {
        $produkModel = new ProdukModel();
        $produk = $produkModel->find($id_produk);

        if (!$produk) {
            // [PERBAIKAN] Redirect ke /inventaris/index
            return redirect()->to('/inventaris/index')->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'  => 'Edit Produk',
            'produk' => $produk,
            'validation' => \Config\Services::validation()
        ];
        return view('inventaris/v_edit_produk', $data); // Pastikan view ini ada
    }

    /**
     * Memperbarui data produk di database
     * (Catatan: Ini tidak menangani update gambar, sesuaikan jika perlu)
     */
    public function update_produk($id_produk)
    {
        $produkModel = new ProdukModel();

        // Validasi sederhana (sama seperti di Karyawan.php)
        $rules = [
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/inventaris/edit_produk/' . $id_produk)->withInput();
        }

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok')
        ];

        $produkModel->update($id_produk, $data);

        // [PERBAIKAN] Redirect ke /inventaris/index
        return redirect()->to('/inventaris/index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus data produk dari database (termasuk gambar)
     */
    public function delete_produk($id_produk)
    {
        $produkModel = new ProdukModel();
        $produk = $produkModel->find($id_produk);

        if ($produk) {
            // Hapus gambar (kecuali default.jpg)
            if ($produk['gambar_produk'] != 'default.jpg') {
                $imagePath = 'uploads/produk/' . $produk['gambar_produk'];
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Hapus file gambar dari server
                }
            }

            // Hapus data dari database
            $produkModel->delete($id_produk);
            // [PERBAIKAN] Redirect ke /inventaris/index
            return redirect()->to('/inventaris/index')->with('success', 'Produk berhasil dihapus.');
        } else {
            // [PERBAIKAN] Redirect ke /inventaris/index
            return redirect()->to('/inventaris/index')->with('error', 'Produk gagal dihapus atau tidak ditemukan.');
        }
    }

    /**
     * [BARU] Menampilkan halaman restok supplier (mengirim data restok)
     */
    public function restok_supplier()
    {
        // Asumsi Anda punya RestokModel
        $restokModel = new RestokModel(); 
        
        $data = [
            'title'       => 'Restok Barang Supplier',
            // [BARU] Mengirim data restok ke view
            'data_restok' => $restokModel->orderBy('id_restok', 'DESC')->findAll()
        ];

        return view('inventaris/restok_supplier', $data);
    }

    /**
     * [BARU] Menyimpan data restok baru ATAU mengupdate data lama
     */
    public function store_restok()
    {
        $restokModel = new RestokModel();
        
        // Ambil data dari form modal
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'status'        => $this->request->getPost('status'),
            'qty'           => $this->request->getPost('qty'),
            'harga_satuan'  => $this->request->getPost('harga_satuan'),
            'total_harga'   => $this->request->getPost('total_harga'), // Pastikan JS menghitung ini
        ];

        // Ambil ID (jika ada, untuk mode edit)
        $id_restok = $this->request->getPost('id_restok');

        if (empty($id_restok)) {
            // Mode INSERT (Data Baru)
            $restokModel->insert($data);
            $message = 'Data restok baru berhasil ditambahkan.';
        } else {
            // Mode UPDATE (Edit Data)
            $restokModel->update($id_restok, $data);
            $message = 'Data restok berhasil diperbarui.';
        }

        return redirect()->to('/inventaris/restok_supplier')->with('success', $message);
    }

    /**
     * [BARU] Menghapus data restok
     */
    public function delete_restok($id_restok)
    {
        $restokModel = new RestokModel();
        
        $restok = $restokModel->find($id_restok);
        
        if ($restok) {
            $restokModel->delete($id_restok);
            return redirect()->to('/inventaris/restok_supplier')->with('success', 'Data restok berhasil dihapus.');
        } else {
            return redirect()->to('/inventaris/restok_supplier')->with('error', 'Data tidak ditemukan.');
        }
    }


    /**
     * Method AJAX untuk pencarian produk (digunakan di POS)
     */
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
                'data' => $item // Kirim semua data produk untuk JS
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }
}