<?php

namespace App\Models;

use CodeIgniter\Model;

class RestokModel extends Model
{
    // [PENTING] Sesuaikan 'restok' dengan nama tabel Anda di database
    protected $table            = 'restok'; 
    
    // [PENTING] Sesuaikan 'id_restok' dengan nama Primary Key Anda
    protected $primaryKey       = 'id_restok'; 

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // [PENTING] Kolom-kolom ini adalah yang diizinkan untuk diisi
    // Kolom ini diambil dari fungsi store_restok di controller Anda
    protected $allowedFields    = [
        'nama_supplier',
        'nama_barang',
        'status',
        'qty',
        'harga_satuan',
        'total_harga'
    ];

    // Jika Anda menggunakan Timestamps (created_at, updated_at)
    // protected $useTimestamps = true; 
}