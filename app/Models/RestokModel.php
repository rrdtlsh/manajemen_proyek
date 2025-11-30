<?php

namespace App\Models;

use CodeIgniter\Model;

class RestokModel extends Model
{
    protected $table            = 'restok';
    protected $primaryKey       = 'id_restok';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields    = [
        'nama_supplier',
        'nama_barang',
        'qty',
        'harga_satuan',
        'total_harga',

        // status inventaris -> owner
        'status',          // Menunggu / Disetujui / Ditolak
        'status_owner',    // Menunggu / Disetujui / Ditolak
        'id_owner',
        'tanggal_approve',

        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ====================================
    //             CUSTOM QUERY
    // ====================================

    // req menunggu persetujuan owner
    public function getPendingForOwner()
    {
        return $this->where('status_owner', 'Menunggu')
            ->orderBy('id_restok', 'DESC')
            ->findAll();
    }

    // req yang sudah disetujui owner
    public function getApproved()
    {
        return $this->where('status_owner', 'Disetujui')
            ->orderBy('id_restok', 'DESC')
            ->findAll();
    }

    // req yang ditolak owner
    public function getRejected()
    {
        return $this->where('status_owner', 'Ditolak')
            ->orderBy('id_restok', 'DESC')
            ->findAll();
    }
}
