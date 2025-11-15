<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KeuanganModel;
use App\Models\PenjualanModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KeuanganController extends BaseController
{
    public function dashboard()
    {
        $keuanganModel = new KeuanganModel();

        // ======================== PEMASUKAN HARI INI =========================
        $pemasukan_hari_ini = $keuanganModel
            ->whereIn('tipe', ['Pemasukan', 'DP'])
            ->where('DATE(tanggal)', date('Y-m-d'))
            ->selectSum('pemasukan')
            ->first()['pemasukan'] ?? 0;

        // ======================== PENGELUARAN HARI INI =========================
        $pengeluaran_hari_ini = $keuanganModel
            ->where('tipe', 'Pengeluaran')
            ->where('DATE(tanggal)', date('Y-m-d'))
            ->selectSum('pengeluaran')
            ->first()['pengeluaran'] ?? 0;

        // ======================== TREND 7 HARI =========================
        $labels = [];
        $pemasukan_data = [];
        $pengeluaran_data = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime($tanggal));

            $pemasukan = $keuanganModel
                ->whereIn('tipe', ['Pemasukan', 'DP'])
                ->where('DATE(tanggal)', $tanggal)
                ->selectSum('pemasukan')
                ->first()['pemasukan'] ?? 0;

            $pengeluaran = $keuanganModel
                ->where('tipe', 'Pengeluaran')
                ->where('DATE(tanggal)', $tanggal)
                ->selectSum('pengeluaran')
                ->first()['pengeluaran'] ?? 0;

            $pemasukan_data[] = (int)$pemasukan;
            $pengeluaran_data[] = (int)$pengeluaran;
        }

        // ======================== TRANSAKSI TERBARU =========================
        $transaksi_terakhir = $keuanganModel
            ->orderBy('tanggal', 'DESC')
            ->limit(10)
            ->findAll();

        // ======================== KIRIM KE VIEW =========================
        return view('dashboard_staff/dashboard_keuangan', [
            'pemasukan_hari_ini' => $pemasukan_hari_ini,
            'pengeluaran_hari_ini' => $pengeluaran_hari_ini,
            'trend_labels' => json_encode($labels),
            'pemasukan_data' => json_encode($pemasukan_data),
            'pengeluaran_data' => json_encode($pengeluaran_data),
            'transaksi_terakhir' => $transaksi_terakhir
        ]);
    }

    public function index()
    {
        // [PERBAIKAN] Arahkan ke rute 'karyawan/'
        return redirect()->to('karyawan/keuangan');
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

    public function exportPemasukanPDF()
    {
        $keuanganModel = new \App\Models\KeuanganModel();

        $rows = $keuanganModel
            ->where('pemasukan >', 0)
            ->orderBy('tanggal', 'DESC')   // GANTI DI SINI
            ->findAll();

        $data = [
            'rows' => $rows
        ];

        $html = view('keuangan/pdf_pemasukan', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("laporan_pemasukan.pdf", ["Attachment" => false]);
    }

    public function exportPengeluaranPdf()
    {
        $keuanganModel = new \App\Models\KeuanganModel();

        $rows = $keuanganModel
            ->where('pengeluaran >', 0)
            ->orderBy('tanggal', 'DESC')   // ← PERBAIKAN DI SINI
            ->findAll();

        $data = [
            'rows' => $rows
        ];

        $html = view('keuangan/pdf_pengeluaran', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("laporan_pengeluaran.pdf", ["Attachment" => false]);
    }

    public function exportPemasukanExcel()
    {
        $model = new \App\Models\KeuanganModel();
        $rows = $model->where('pemasukan >', 0)->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Kolom
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Keterangan');
        $sheet->setCellValue('C1', 'Jumlah');
        $sheet->setCellValue('D1', 'Tanggal');

        // Isi data
        $rowNum = 2;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['id_keuangan']);
            $sheet->setCellValue('B' . $rowNum, $row['keterangan']);
            $sheet->setCellValue('C' . $rowNum, $row['pemasukan']);
            $sheet->setCellValue('D' . $rowNum, $row['tanggal']);
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_pemasukan_' . date('Ymd_His') . '.xlsx';

        // Output ke browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPengeluaranExcel()
    {
        $model = new \App\Models\KeuanganModel();

        // Ambil data pengeluaran
        $rows = $model
            ->where('tipe', 'Pengeluaran')
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        // Membuat file Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Keterangan');
        $sheet->setCellValue('C1', 'Jumlah');
        $sheet->setCellValue('D1', 'Tanggal');

        // Isi data
        $x = 2;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $x, $row['id_keuangan']);
            $sheet->setCellValue('B' . $x, $row['keterangan']);
            $sheet->setCellValue('C' . $x, $row['pengeluaran']);
            $sheet->setCellValue('D' . $x, $row['tanggal']);
            $x++;
        }

        // Output Excel ke browser
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'laporan_pengeluaran_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
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
}
