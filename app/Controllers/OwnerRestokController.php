<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RestokModel;
use App\Models\KeuanganModel; 

class OwnerRestokController extends BaseController
{
    public function index()
    {
        $restokModel = new RestokModel();

        $data = [
            'title' => 'Daftar Restok Supplier',
            'data_restok' => $restokModel->orderBy('id_restok', 'DESC')->findAll()
        ];

        return view('owner/restok_approval', $data);
    }

    public function update_status()
    {
        $restokModel = new RestokModel();
        $keuanganModel = new KeuanganModel(); // <--- Load Model Keuangan
        
        $id_restok = $this->request->getPost('id_restok');
        $status_baru = $this->request->getPost('status_owner');

        if ($id_restok && in_array($status_baru, ['Menunggu', 'Disetujui', 'Ditolak'])) {
            
            // 1. Ambil data restok yang lama untuk cek
            $dataRestok = $restokModel->find($id_restok);

            // Cek agar tidak menduplikasi pengeluaran jika diklik "Disetujui" berkali-kali
            // Kita hanya catat ke keuangan jika status sebelumnya BUKAN Disetujui, dan status baru ADALAH Disetujui
            $perluCatatKeuangan = ($dataRestok['status_owner'] != 'Disetujui' && $status_baru == 'Disetujui');
            
            // Update Data Restok
            $updateData = [
                'status_owner' => $status_baru,
                'status'       => $status_baru 
            ];

            if ($status_baru == 'Disetujui') {
                $updateData['tanggal_approve'] = date('Y-m-d H:i:s');
                $updateData['id_owner'] = session()->get('user_id');
            }

            $restokModel->update($id_restok, $updateData);

            // 2. LOGIKA BARU: Catat ke Tabel Keuangan Otomatis
            if ($perluCatatKeuangan) {
                $keuanganModel->insert([
                    'tanggal'     => date('Y-m-d'), // Tanggal hari ini (saat di-approve)
                    'tipe'        => 'Pengeluaran',
                    'pemasukan'   => 0,
                    'pengeluaran' => $dataRestok['total_harga'], // Ambil total harga dari data restok
                    'keterangan'  => 'Belanja Stok: ' . $dataRestok['nama_barang'] . ' (' . $dataRestok['qty'] . ' Pcs) - ' . $dataRestok['nama_supplier'],
                    'id_user'     => session()->get('user_id')
                ]);
            }

            return redirect()->back()->with('success', 'Status diperbarui' . ($perluCatatKeuangan ? ' & tercatat di Pengeluaran.' : '.'));
        }

        return redirect()->back()->with('error', 'Gagal mengubah status.');
    }

    public function delete($id)
    {
        $restokModel = new RestokModel();
        $restokModel->delete($id);
        return redirect()->back()->with('success', 'Data restok berhasil dihapus.');
    }
}