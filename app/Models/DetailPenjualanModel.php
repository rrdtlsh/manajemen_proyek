<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
    protected $table            = 'detail_penjualan';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
   
    protected $allowedFields    = ['id_penjualan', 'id_produk', 'qty', 'harga_satuan'];
}