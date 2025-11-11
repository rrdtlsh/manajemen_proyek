<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id_produk';
    protected $useAutoIncrement = true;

    // Diambil dari file Anda sebelumnya
    protected $allowedFields    = ['nama_produk', 'kategori', 'stok', 'harga', 'gambar', 'deskripsi'];

    /**
     * Mengurangi stok produk.
     * (Ini fungsi yang menurut error "undefined")
     */
    public function kurangiStok($id_produk, $jumlah)
    {
        $this->set('stok', 'stok - ' . (int)$jumlah, false)
            ->where('id_produk', $id_produk)
            ->update();
    }

    /**
     * Menambah stok produk (untuk mengembalikan stok saat edit).
     */
    public function tambahStok($id_produk, $jumlah)
    {
        $this->set('stok', 'stok + ' . (int)$jumlah, false)
            ->where('id_produk', $id_produk)
            ->update();
    }
}
