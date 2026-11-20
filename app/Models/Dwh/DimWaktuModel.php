<?php

namespace App\Models\Dwh;

use CodeIgniter\Model;

class DimWaktuModel extends Model
{
    protected $DBGroup          = 'dwh';
    protected $table            = 'dim_waktu';
    protected $primaryKey       = 'sk_waktu';
    
    protected $allowedFields    = ['tanggal', 'hari', 'bulan', 'tahun', 'kuartal', 'nama_hari', 'nama_bulan'];

    public function getSkHariIni()
    {
        $today = date('Y-m-d');
        return $this->where('tanggal', $today)->first()['sk_waktu'] ?? null;
    }
}