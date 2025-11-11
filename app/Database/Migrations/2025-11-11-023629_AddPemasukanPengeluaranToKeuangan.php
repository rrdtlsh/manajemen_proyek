<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPemasukanPengeluaranToKeuangan extends Migration
{
    public function up()
    {
        $fields = [
            'pemasukan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2', // Sesuai database.sql Anda
                'null'       => true,     // Mengizinkan null
                'default'    => '0.00',
                'after'      => 'tipe',   // Menempatkan setelah 'tipe'
            ],
            'pengeluaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => '0.00',
                'after'      => 'pemasukan', // Menempatkan setelah 'pemasukan'
            ],
        ];

        $this->forge->addColumn('keuangan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('keuangan', ['pemasukan', 'pengeluaran']);
    }
}
