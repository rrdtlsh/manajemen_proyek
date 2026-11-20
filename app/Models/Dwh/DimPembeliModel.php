<?php

namespace App\Models\Dwh;

use CodeIgniter\Model;

class DimPembeliModel extends Model
{
    protected $DBGroup          = 'dwh';
    protected $table            = 'dim_pembeli';
    protected $primaryKey       = 'sk_pembeli';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = [
        'id_pelanggan_sumber', // ID dari tabel pelanggan OLTP
        'nama_pembeli',
        'no_hp',
        'kategori_pelanggan',  // 'Baru', 'Loyal', 'Reseller' (Hasil Transformasi)
        'alamat_domisili'
    ];

    /**
     * Helper untuk mendapatkan SK Pembeli (Dipakai saat ETL Penjualan)
     */
    public function getSkByIdSumber($id_sumber)
    {
        // Jika transaksi tanpa pelanggan (pembeli umum), kembalikan SK default (misal ID 1)
        if (empty($id_sumber)) return 1; 

        $row = $this->where('id_pelanggan_sumber', $id_sumber)->get()->getRow();
        
        // Jika belum ada di dimensi, bisa return null atau buat baru on-the-fly (tergantung strategi ETL)
        return $row ? $row->sk_pembeli : null; 
    }
}