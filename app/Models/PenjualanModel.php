<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id_penjualan';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = ['tanggal', 'total', 'status_pembayaran', 'jumlah_dp', 'id_user', 'metode_pembayaran']; 
}