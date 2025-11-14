<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'supplier';
    protected $primaryKey       = 'id_supplier';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'nama_supplier',
        'alamat',
        'no_telp'
    ];
}
