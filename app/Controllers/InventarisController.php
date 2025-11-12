<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\RestokModel; // Pastikan Anda memiliki Model ini

class InventarisController extends BaseController
{
    public function dashboard()
    {
        $data = [
            'username' => session()->get('username'),
            'title' => 'Dashboard Inventaris'
        ];
        return view('dashboard_staff/dashboard_inventaris', $data);
    }

    public function index()
    {
        $produkModel = new ProdukModel();
        $data = [
            'title'  => 'Manajemen Inventaris',
            'produk' => $produkModel->orderBy('id_produk', 'DESC')->findAll()
        ];
        return view('inventaris/input_inventaris', $data);
    }

    public function tambah_produk()
    {
        $data = [
            'title' => 'Tambah Produk Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('inventaris/v_tambah_produk', $data);
    }

    public function store_produk()
    {
        $produkModel = new ProdukModel();
        $rules = [
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'gambar_produk' => [
                'label' => 'Foto Produk',
                'rules' => 'is_image[gambar_produk]'
                    . '|mime_in[gambar_produk,image/jpg,image/jpeg,image/png,image/webp]'
                    . '|max_size[gambar_produk,2048]',
            ],
        ];

        if (!$this->validate($rules)) {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/inventaris/tambah')->withInput();
        }

        $img = $this->request->getFile('gambar_produk');
        $namaGambar = 'default.jpg';

        if ($img && $img->isValid() && !$img->hasMoved()) {
            $namaGambar = $img->getRandomName();
            $img->move('uploads/produk/', $namaGambar);
        }

        $data = [
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'gambar_produk' => $namaGambar,
        ];

        $produkModel->insert($data);

        // [PERBAIKAN] Redirect ke rute 'karyawan/'
        return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit_produk($id_produk)
    {
        $produkModel = new ProdukModel();
        $produk = $produkModel->find($id_produk);

        if (!$produk) {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('/karyawan/inventaris')->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'  => 'Edit Produk',
            'produk' => $produk,
            'validation' => \Config\Services::validation()
        ];
        return view('inventaris/v_edit_produk', $data);
    }

    public function update_produk($id_produk)
    {
        $produkModel = new ProdukModel();
        $rules = [
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('karyawan/inventaris/edit/' . $id_produk)->withInput();
        }

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok')
        ];

        $produkModel->update($id_produk, $data);

        // [PERBAIKAN] Redirect ke rute 'karyawan/'
        return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil diperbarui.');
    }

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
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('/karyawan/inventaris')->with('success', 'Produk berhasil dihapus.');
        } else {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('/karyawan/inventaris')->with('error', 'Produk gagal dihapus atau tidak ditemukan.');
        }
    }

    public function restok_supplier()
    {
        $restokModel = new RestokModel();
        $data = [
            'title'       => 'Restok Barang Supplier',
            'data_restok' => $restokModel->orderBy('id_restok', 'DESC')->findAll()
        ];
        return view('inventaris/restok_supplier', $data);
    }

    public function store_restok()
    {
        // (Fungsi ini sepertinya belum ada di Karyawan.php, tapi saya perbaiki redirect-nya)
        $restokModel = new RestokModel();
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'status'        => $this->request->getPost('status'),
            'qty'           => $this->request->getPost('qty'),
            'harga_satuan'  => $this->request->getPost('harga_satuan'),
            'total_harga'   => $this->request->getPost('total_harga'),
        ];
        $id_restok = $this->request->getPost('id_restok');

        if (empty($id_restok)) {
            $restokModel->insert($data);
            $message = 'Data restok baru berhasil ditambahkan.';
        } else {
            $restokModel->update($id_restok, $data);
            $message = 'Data restok berhasil diperbarui.';
        }

        // [PERBAIKAN] Redirect ke rute 'karyawan/'
        return redirect()->to('/karyawan/inventaris/restok')->with('success', $message);
    }

    public function delete_restok($id_restok)
    {
        // (Fungsi ini sepertinya belum ada di Karyawan.php, tapi saya perbaiki redirect-nya)
        $restokModel = new RestokModel();
        $restok = $restokModel->find($id_restok);

        if ($restok) {
            $restokModel->delete($id_restok);
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('/karyawan/inventaris/restok')->with('success', 'Data restok berhasil dihapus.');
        } else {
            // [PERBAIKAN] Redirect ke rute 'karyawan/'
            return redirect()->to('/karyawan/inventaris/restok')->with('error', 'Data tidak ditemukan.');
        }
    }
}
