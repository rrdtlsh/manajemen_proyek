<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
    protected $table            = 'detail_penjualan';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;

    protected $allowedFields    = ['id_penjualan', 'id_produk', 'qty', 'harga_satuan'];

    /**
     * [FUNGSI BARU]
     * Mengambil semua item detail untuk satu ID penjualan,
     * sekaligus mengambil nama produk dari tabel 'produk'.
     */
    public function getDetailItems($id_penjualan)
    {
        return $this->db->table('detail_penjualan as dp')
            ->select('dp.*, p.nama_produk, p.gambar_produk') // Ambil semua dari detail, dan nama_produk
            ->join('produk as p', 'p.id_produk = dp.id_produk', 'left')
            ->where('dp.id_penjualan', $id_penjualan)
            ->get()
            ->getResultArray(); // Kembalikan sebagai array
    }
}
