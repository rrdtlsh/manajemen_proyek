<?php

namespace App\Models\Dwh;

use CodeIgniter\Model;

class FactInventarisModel extends Model
{
    protected $DBGroup          = 'dwh';
    protected $table            = 'Fact_Inventaris';
    protected $primaryKey       = 'id_fakta_inventaris';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'sk_waktu', 'sk_produk', 'sk_supplier', 'sk_lokasi',
        'jenis_pergerakan', 'jumlah_masuk', 'jumlah_keluar', 'sisa_stok_harian'
    ];

    /**
     * Analisis Dead Stock (Barang Mengendap)
     * Strategi: Cari produk yang TIDAK ADA transaksinya di Fact_Penjualan dalam X hari terakhir
     */
    public function getDeadStock($hari = 90)
    {
        $db = \Config\Database::connect('dwh');
        
        $cutoffDate = date('Y-m-d', strtotime("-$hari days"));

        // Subquery: Cari produk yang LAKU
        $soldProductsQuery = $db->table('Fact_Penjualan')
            ->select('sk_produk')
            ->join('dim_waktu', 'dim_waktu.sk_waktu = Fact_Penjualan.sk_waktu')
            ->where('dim_waktu.tanggal >=', $cutoffDate)
            ->groupBy('sk_produk')
            ->getCompiledSelect();

        // Query Utama
        // PENTING: Perhatikan bagian "AS ..." (Alias)
        return $db->table('dim_produk')
            ->select('id_produk_sumber')       // Untuk cek ke toko
            ->select('nama_produk')            // Untuk View
            ->select('kode_produk_asli as kode_produk') // ALIAS PENTING!
            ->select('kategori as nama_kategori')       // ALIAS PENTING!
            ->select('harga_jual_saat_ini as harga_jual') // ALIAS PENTING!
            ->select('0 as stok')              // Placeholder
            ->where("sk_produk NOT IN ($soldProductsQuery)", null, false)
            ->limit(5)
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil Tren Pergerakan Stok (Masuk vs Keluar) 7 Hari Terakhir
     */
    public function getPergerakanStok($limit = 7)
    {
        return $this->select('dim_waktu.tanggal, SUM(jumlah_masuk) as total_masuk, SUM(jumlah_keluar) as total_keluar')
                    ->join('dim_waktu', 'dim_waktu.sk_waktu = Fact_Inventaris.sk_waktu')
                    ->groupBy('dim_waktu.tanggal')
                    ->orderBy('dim_waktu.tanggal', 'DESC')
                    ->limit($limit)
                    ->get()->getResultArray();
    }
}