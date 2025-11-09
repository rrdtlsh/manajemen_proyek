<?php

namespace App\Models;

use CodeIgniter\Model;

class KeuanganModel extends Model
{
    protected $table            = 'keuangan';
    protected $primaryKey       = 'id_transaksi';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_penjualan', 'jenis_transaksi', 'jumlah', 'tanggal', 'keterangan'];
}