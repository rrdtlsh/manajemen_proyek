<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\SupplierModel;
use App\Models\KategoriModel;

class OwnerProdukController extends BaseController
{
    protected $produkModel;
    protected $supplierModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->produkModel   = new ProdukModel();
        $this->supplierModel = new SupplierModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Manajemen Produk (Owner)',
            // Mengurutkan berdasarkan ID terbaru
            'produk'     => $this->produkModel->orderBy('id_produk', 'DESC')->findAll(),
            'suppliers'  => $this->supplierModel->findAll(),
            'kategori'   => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('owner/manajemen_produk', $data);
    }

    // --- TAMBAH PRODUK BARU ---
    public function store()
    {
        // Validasi input
        if (!$this->validate([
            'kode_produk'   => 'required|is_unique[produk.kode_produk]',
            'nama_produk'   => 'required',
            'id_kategori'   => 'required',
            'id_supplier'   => 'required',
            'harga'         => 'required|numeric',
            'stok'          => 'required|numeric',
            'tanggal_masuk' => 'required',
            'gambar_produk' => 'permit_empty|is_image[gambar_produk]|mime_in[gambar_produk,image/jpg,image/jpeg,image/png]|max_size[gambar_produk,2048]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek kembali inputan Anda.');
        }

        // Upload Gambar
        $fileGambar = $this->request->getFile('gambar_produk');
        $namaGambar = 'default.jpg';
        
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/produk/', $namaGambar);
        }

        // Simpan ke Database
        $this->produkModel->save([
            'kode_produk'   => $this->request->getPost('kode_produk'),
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'id_supplier'   => $this->request->getPost('id_supplier'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'gambar_produk' => $namaGambar
        ]);

        return redirect()->to('/owner/manajemen_produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    // --- UPDATE PRODUK ---
    public function update($id)
    {
        // Cek produk lama
        $produkLama = $this->produkModel->find($id);
        if (!$produkLama) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Rule validasi (kode produk unique kecuali punya sendiri)
        if (!$this->validate([
            'kode_produk'   => "required|is_unique[produk.kode_produk,id_produk,{$id}]",
            'nama_produk'   => 'required',
            'harga'         => 'required|numeric',
            'stok'          => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal saat update.');
        }

        // Upload Gambar Baru (Jika ada)
        $fileGambar = $this->request->getFile('gambar_produk');
        $namaGambar = $produkLama['gambar_produk']; // Default pakai gambar lama

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // Hapus gambar lama jika bukan default
            if ($produkLama['gambar_produk'] != 'default.jpg' && file_exists('uploads/produk/' . $produkLama['gambar_produk'])) {
                unlink('uploads/produk/' . $produkLama['gambar_produk']);
            }
            // Upload baru
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/produk/', $namaGambar);
        }

        // Update Data
        $this->produkModel->update($id, [
            'kode_produk'   => $this->request->getPost('kode_produk'),
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'id_supplier'   => $this->request->getPost('id_supplier'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'gambar_produk' => $namaGambar
        ]);

        return redirect()->to('/owner/manajemen_produk')->with('success', 'Data produk berhasil diperbarui.');
    }

    // --- HAPUS PRODUK ---
    public function delete($id)
    {
        $produk = $this->produkModel->find($id);

        if ($produk) {
            // Hapus file gambar
            if ($produk['gambar_produk'] != 'default.jpg' && file_exists('uploads/produk/' . $produk['gambar_produk'])) {
                unlink('uploads/produk/' . $produk['gambar_produk']);
            }
            $this->produkModel->delete($id);
            return redirect()->back()->with('success', 'Produk berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus. Produk tidak ditemukan.');
    }
}