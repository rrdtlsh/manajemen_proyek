<?php

namespace App\Models\Dwh;

use CodeIgniter\Model;

class DimProdukModel extends Model
{
    protected $DBGroup          = 'dwh';
    protected $table            = 'dim_produk';
    protected $primaryKey       = 'sk_produk'; // Surrogate Key
    protected $allowedFields    = [
        'id_produk_sumber', // ID asli dari OLTP
        'nama_produk', 
        'kategori', 
        'harga_beli_terakhir', 
        'harga_jual_terakhir'
    ];

    /**
     * Helper untuk mendapatkan Surrogate Key berdasarkan ID Asli (Untuk ETL)
     */
    public function getSkByIdSumber($id_sumber)
    {
        $row = $this->where('id_produk_sumber', $id_sumber)
                    ->get()->getRow();
        return $row ? $row->sk_produk : null;
    }
}