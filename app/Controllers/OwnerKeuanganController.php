<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KeuanganModel;
use App\Models\PenjualanModel;

class OwnerKeuanganController extends BaseController
{
    public function index()
    {
        $keuanganModel = new KeuanganModel();
        $penjualanModel = new PenjualanModel();

        // 1. Ambil Filter dari URL (GET request)
        $year = $this->request->getGet('year') ?? date('Y');
        $quarter = $this->request->getGet('quarter');

        // 2. Tentukan Range Tanggal Berdasarkan Filter
        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        if ($quarter) {
            if ($quarter == 'Q1') {
                $startDate = "$year-01-01";
                $endDate = "$year-03-31";
            } elseif ($quarter == 'Q2') {
                $startDate = "$year-04-01";
                $endDate = "$year-06-30";
            } elseif ($quarter == 'Q3') {
                $startDate = "$year-07-01";
                $endDate = "$year-09-30";
            } elseif ($quarter == 'Q4') {
                $startDate = "$year-10-01";
                $endDate = "$year-12-31";
            }
        }

        // 3. Query Data Keuangan (Arus Kas)
        $keuanganBuilder = $keuanganModel->where('tanggal >=', $startDate)->where('tanggal <=', $endDate);
        $laporan = (clone $keuanganBuilder)->orderBy('tanggal', 'ASC')->findAll();

        // 4. Hitung Ringkasan (Cards)
        $totalPemasukan = (clone $keuanganBuilder)->whereIn('tipe', ['Pemasukan', 'DP'])->selectSum('pemasukan')->first()['pemasukan'] ?? 0;
        $totalPengeluaran = (clone $keuanganBuilder)->where('tipe', 'Pengeluaran')->selectSum('pengeluaran')->first()['pengeluaran'] ?? 0;
        $labaRugi = $totalPemasukan - $totalPengeluaran;

        // 5. Hitung Total Transaksi Lunas (untuk Card Info)
        $penjualanBuilder = $penjualanModel->where('status_pembayaran', 'Lunas')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate);

        $totalTransaksi = (clone $penjualanBuilder)->countAllResults();

        $data = [
            'title'               => 'Laporan Keuangan (Owner)',
            'laporan'             => $laporan,
            'total_pemasukan'     => $totalPemasukan,
            'total_pengeluaran'   => $totalPengeluaran,
            'laba_rugi'           => $labaRugi,
            'total_transaksi'     => $totalTransaksi,
            'selectedYear'        => $year,
            'selectedQuarter'     => $quarter,
        ];

        return view('owner/laporan_keuangan', $data);
    }
}