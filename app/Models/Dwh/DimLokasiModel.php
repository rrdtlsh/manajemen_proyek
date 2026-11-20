<?php

namespace App\Models\Dwh;

use CodeIgniter\Model;

class DimLokasiModel extends Model
{
    protected $DBGroup          = 'dwh';
    protected $table            = 'dim_lokasi';
    protected $primaryKey       = 'sk_lokasi';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = [
        'id_lokasi_sumber', // ID asli dari tabel toko/cabang di OLTP (jika ada)
        'tipe_lokasi',      // 'Toko Fisik', 'Online', 'Gudang'
        'nama_wilayah',     // 'Banjarmasin Utara', 'Banjarbaru', dll
        'alamat_detail'
    ];
}