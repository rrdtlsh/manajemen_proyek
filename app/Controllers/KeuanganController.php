<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KeuanganModel;
use App\Models\PenjualanModel;
use CodeIgniter\I18n\Time;

class KeuanganController extends BaseController
{
    public function index()
    {
        // [PERBAIKAN] Arahkan ke rute 'karyawan/'
        return redirect()->to('karyawan/keuangan/laporan');
    }

    public function pemasukan()
    {
        $keuanganModel = new KeuanganModel();
        $data = [
            'title' => 'Laporan Pemasukan',
            'laporan' => $keuanganModel
                ->whereIn('tipe', ['Pemasukan', 'DP'])
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];
        return view('keuangan/pemasukan', $data);
    }

    public function pengeluaran()
    {
        $keuanganModel = new KeuanganModel();
        $data = [
            'title' => 'Laporan Pengeluaran',
            'laporan' => $keuanganModel
                ->where('tipe', 'Pengeluaran')
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];
        return view('keuangan/pengeluaran', $data);
    }

    public function laporanKeuangan()
    {
        $keuanganModel = new KeuanganModel();
        $penjualanModel = new PenjualanModel();
        $year = $this->request->getGet('year') ?? date('Y');
        $quarter = $this->request->getGet('quarter');

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

        $keuanganBuilder = $keuanganModel->where('tanggal >=', $startDate)->where('tanggal <=', $endDate);
        $laporan = (clone $keuanganBuilder)->orderBy('tanggal', 'ASC')->findAll();

        $totalPemasukan = (clone $keuanganBuilder)->whereIn('tipe', ['Pemasukan', 'DP'])->selectSum('pemasukan')->first()['pemasukan'] ?? 0;
        $totalPengeluaran = (clone $keuanganBuilder)->where('tipe', 'Pengeluaran')->selectSum('pengeluaran')->first()['pengeluaran'] ?? 0;
        $labaRugi = $totalPemasukan - $totalPengeluaran;

        $penjualanBuilder = $penjualanModel->where('status_pembayaran', 'Lunas')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate);

        $totalTransaksi = (clone $penjualanBuilder)->countAllResults();
        $totalNilaiTransaksi = (clone $penjualanBuilder)->selectSum('total')->first()['total'] ?? 0;
        $avgTransaksi = ($totalTransaksi > 0) ? $totalNilaiTransaksi / $totalTransaksi : 0;

        $data = [
            'title'               => 'Laporan Keuangan',
            'laporan'             => $laporan,
            'total_pemasukan'     => $totalPemasukan,
            'total_pengeluaran'   => $totalPengeluaran,
            'laba_rugi'           => $labaRugi,
            'total_transaksi'     => $totalTransaksi,
            'avg_transaksi'       => $avgTransaksi,
            'selectedYear'        => $year,
            'selectedQuarter'     => $quarter,
        ];

        return view('keuangan/laporan', $data);
    }

    public function input_keuangan()
    {
        $data = [
            'title' => 'Input Manual Keuangan'
        ];
        return view('keuangan/input_keuangan', $data);
    }

    public function store_keuangan()
    {
        $keuanganModel = new KeuanganModel();
        $tipe = $this->request->getPost('tipe');
        $jumlah = $this->request->getPost('jumlah');

        if (empty($tipe) || empty($jumlah) || empty($this->request->getPost('tanggal'))) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi.');
        }

        $data = [
            'tanggal'     => $this->request->getPost('tanggal'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'tipe'        => $tipe,
            'pemasukan'   => ($tipe == 'Pemasukan') ? $jumlah : 0,
            'pengeluaran' => ($tipe == 'Pengeluaran') ? $jumlah : 0,
            'id_user'     => session()->get('user_id')
        ];

        $keuanganModel->insert($data);

        // [PERBAIKAN] Redirect ke rute 'karyawan/'
        if ($tipe == 'Pemasukan') {
            return redirect()->to('karyawan/keuangan/pemasukan')->with('success', 'Data pemasukan manual berhasil ditambahkan.');
        } else {
            return redirect()->to('karyawan/keuangan/pengeluaran')->with('success', 'Data pengeluaran manual berhasil ditambahkan.');
        }
    }
}
