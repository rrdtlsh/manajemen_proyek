<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RestokModel;

class OwnerRestokController extends BaseController
{
    public function index()
    {
        $restokModel = new RestokModel();

        $data = [
            'title' => 'Persetujuan Restok Supplier',
            'data_restok' => $restokModel->orderBy('id_restok', 'DESC')->findAll()
        ];

        return view('owner/restok_approval', $data);
    }

    public function approve($id)
    {
        $restokModel = new RestokModel();

        $restokModel->update($id, [
            'status_owner' => 'Disetujui'
        ]);

        return redirect()->back()->with('success', 'Restok berhasil disetujui Owner.');
    }

    public function reject($id)
    {
        $restokModel = new RestokModel();

        $restokModel->update($id, [
            'status_owner' => 'Ditolak'
        ]);

        return redirect()->back()->with('success', 'Restok berhasil ditolak Owner.');
    }
}
