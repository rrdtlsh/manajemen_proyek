<?php

namespace App\Models\Dwh;

use CodeIgniter\Model;

class DimSupplierModel extends Model
{
    protected $DBGroup          = 'dwh';
    protected $table            = 'dim_supplier';
    protected $primaryKey       = 'sk_supplier';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = [
        'id_supplier_sumber', // ID dari tabel supplier OLTP
        'nama_supplier',
        'kontak_person',
        'no_telepon',
        'alamat_supplier'
    ];
}