<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// [PERBAIKAN PENTING] Tambahkan SEMUA model yang kita butuhkan
use App\Models\KeuanganModel;
use App\Models\PenjualanModel;
use CodeIgniter\I18n\Time; // Pastikan ini juga ada

class Keuangan extends BaseController
{
    /**
     * Halaman index akan diarahkan ke laporan utama
     */
    public function index()
    {
        return redirect()->to('keuangan/laporanKeuangan');
    }

    /**
     * [PERBAIKAN] Kode sudah diisi
     * Menampilkan halaman Laporan Pemasukan
     */
    public function keuanganPemasukan()
    {
        $keuanganModel = new KeuanganModel();

        $data = [
            'title' => 'Laporan Pemasukan',
            'laporan' => $keuanganModel
                ->whereIn('tipe', ['Pemasukan', 'DP']) // Hanya ambil Pemasukan dan DP
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];

        return view('keuangan/pemasukan', $data); // Pastikan view 'keuangan/pemasukan.php' ada
    }

    /**
     * [PERBAIKAN] Kode sudah diisi
     * Menampilkan halaman Laporan Pengeluaran
     */
    public function keuanganPengeluaran()
    {
        $keuanganModel = new KeuanganModel();

        $data = [
            'title' => 'Laporan Pengeluaran',
            'laporan' => $keuanganModel
                ->where('tipe', 'Pengeluaran')
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];

        return view('keuangan/pengeluaran', $data); // Pastikan view 'keuangan/pengeluaran.php' ada
    }

    /**
     * [PERBAIKAN] Kode sudah diisi
     * Menampilkan halaman Laporan Keuangan utama
     */
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
            'title'              => 'Laporan Keuangan',
            'laporan'            => $laporan,
            'total_pemasukan'    => $totalPemasukan,
            'total_pengeluaran'  => $totalPengeluaran,
            'laba_rugi'          => $labaRugi,
            'total_transaksi'    => $totalTransaksi,
            'avg_transaksi'      => $avgTransaksi,
            'selectedYear'       => $year,
            'selectedQuarter'    => $quarter,
        ];

        return view('keuangan/laporan', $data); // Pastikan view 'keuangan/laporan.php' ada
    }

    /**
     * [PERBAIKAN] Kode sudah diisi
     * Menampilkan form input keuangan manual
     */
    public function input_keuangan()
    {
        $data = [
            'title' => 'Input Manual Keuangan'
        ];
        return view('keuangan/input_keuangan', $data); // Pastikan view 'keuangan/input_keuangan.php' ada
    }

    /**
     * [PERBAIKAN] Kode sudah diisi
     * Menyimpan data keuangan manual
     */
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

        // [PERBAIKAN] Redirect ke rute keuangan yang baru
        return redirect()->to('keuangan/pengeluaran')->with('success', 'Data pengeluaran manual berhasil ditambahkan.');
    }
}