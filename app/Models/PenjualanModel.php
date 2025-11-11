<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id_penjualan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'tanggal',
        'total',
        'status_pembayaran',
        'jumlah_dp',
        'id_user',
        'id_pelanggan',
        'metode_pembayaran'
    ];

    protected $useTimestamps = false;


    /**
     * --------------------------------------------------------------------
     * FUNGSI KUSTOM UNTUK JOIN
     * --------------------------------------------------------------------
     * Gunakan fungsi-fungsi ini di Controller Anda, BUKAN $model->findAll()
     */


    /**
     * Mengambil semua riwayat penjualan dengan data pelanggan dan user.
     * Gunakan ini untuk halaman riwayat_penjualan.php Anda.
     */
    public function getRiwayatPenjualan()
    {
        return $this->db->table('penjualan')
            ->select('penjualan.*, pelanggan.nama_pelanggan, user.username')

            // JOIN ke tabel pelanggan (LEFT JOIN agar data penjualan tetap tampil meski pelanggan null)
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')

            // JOIN ke tabel user (LEFT JOIN agar data penjualan tetap tampil meski user null)
            ->join('user', 'user.id_user = penjualan.id_user', 'left')

            ->orderBy('penjualan.tanggal', 'DESC') // Urutkan berdasarkan tanggal terbaru
            ->get()
            ->getResultArray(); // Kembalikan sebagai array
    }

    /**
     * Mengambil SATU data penjualan spesifik dengan data pelanggan dan user.
     * Gunakan ini untuk halaman EDIT Anda.
     */
    public function getPenjualanById($id_penjualan)
    {
        return $this->db->table('penjualan')
            ->select('penjualan.*, pelanggan.nama_pelanggan, user.username')

            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
            ->join('user', 'user.id_user = penjualan.id_user', 'left')

            ->where('penjualan.id_penjualan', $id_penjualan) // Cari berdasarkan ID

            ->get()
            ->getRowArray(); // Kembalikan satu baris sebagai array
    }

    /**
     * [MODIFIKASI] Mengambil data ringkasan untuk Dashboard Karyawan.
     */
    public function getDashboardData()
    {
        $today = date('Y-m-d');
        $id_user = session()->get('user_id');

        // 1. Penjualan Hari Ini (Jumlah Transaksi)
        $penjualan_hari_ini = $this->where('tanggal', $today)
            ->where('id_user', $id_user)
            ->countAllResults();

        // 2. Total Omzet Hari Ini
        $omzet_hari_ini = $this->selectSum('total', 'total_omzet')
            ->where('tanggal', $today)
            ->where('id_user', $id_user)
            ->get()
            ->getRow()
            ->total_omzet;

        // 3. Produk Terjual Hari Ini (Total Qty dari detail)
        $db = \Config\Database::connect();
        $produk_terjual_hari_ini = $db->table('detail_penjualan as dp')
            ->join('penjualan as p', 'p.id_penjualan = dp.id_penjualan')
            ->where('p.tanggal', $today)
            ->where('p.id_user', $id_user)
            ->selectSum('dp.qty', 'total_qty')
            ->get()
            ->getRow()
            ->total_qty;

        // 4. Transaksi Terakhir (5 Transaksi)
        $transaksi_terakhir = $this
            ->select('penjualan.*, pelanggan.nama_pelanggan')
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
            ->where('penjualan.id_user', $id_user)
            ->orderBy('penjualan.id_penjualan', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // 5. [BARU] Panggil data chart
        $chart_data = $this->getChartDataLast7Days();

        // Kembalikan semua data sebagai array
        return [
            'penjualan_hari_ini'      => $penjualan_hari_ini,
            'omzet_hari_ini'          => $omzet_hari_ini ?? 0,
            'produk_terjual_hari_ini' => $produk_terjual_hari_ini ?? 0,
            'transaksi_terakhir'      => $transaksi_terakhir,
            'chart_labels'            => json_encode($chart_data['labels']), // Langsung encode ke JSON
            'chart_data'              => json_encode($chart_data['data'])    // Langsung encode ke JSON
        ];
    }

    public function getChartDataLast7Days()
    {
        $id_user = session()->get('user_id');
        $labels = [];
        $data = [];

        // 1. Buat array 7 tanggal terakhir (label)
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime($date)); // Format: "11 Nov"

            // 2. Ambil data dari DB untuk tanggal tsb
            $query = $this->selectSum('total', 'total_omzet')
                ->where('tanggal', $date)
                ->where('id_user', $id_user)
                ->get()
                ->getRow();

            // 3. Masukkan datanya (atau 0 jika tidak ada penjualan)
            $data[] = ($query->total_omzet) ? (float)$query->total_omzet : 0;
        }

        return [
            'labels' => $labels,
            'data'   => $data
        ];
    }
}
