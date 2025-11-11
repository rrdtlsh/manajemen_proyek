<?php

namespace App\Models;

use CodeIgniter\Model;

class KeuanganModel extends Model
{
    protected $table          = 'keuangan';
    protected $primaryKey     = 'id_keuangan'; // <-- UBAH KE SINI
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'tanggal',
        'keterangan',
        'pemasukan',
        'pengeluaran',
        'tipe',
        'id_user'
    ];
}
