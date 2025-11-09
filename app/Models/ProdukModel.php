<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id_produk';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['nama_produk', 'kategori', 'stok', 'harga', 'gambar', 'deskripsi'];
    
    /**
     * Mengurangi stok produk.
     * Menggunakan query SQL 'SET stok = stok - ?' agar aman dari race condition.
     */
    public function kurangiStok($id_produk, $jumlah)
    {
        $this->set('stok', 'stok - ' . (int)$jumlah, false)
             ->where('id_produk', $id_produk)
             ->update();
    }
}