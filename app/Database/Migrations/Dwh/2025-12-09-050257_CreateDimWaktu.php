<?php

namespace App\Database\Migrations\Dwh;
use CodeIgniter\Database\Migration;

class CreateDimWaktu extends Migration
{
    protected $DBGroup = 'dwh'; // PENTING!

    public function up()
    {
        $this->forge->addField([
            'sk_waktu'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tanggal'    => ['type' => 'DATE'],
            'hari'       => ['type' => 'INT', 'constraint' => 2],
            'nama_hari'  => ['type' => 'VARCHAR', 'constraint' => 20],
            'minggu_ke'  => ['type' => 'INT', 'constraint' => 2],
            'bulan'      => ['type' => 'INT', 'constraint' => 2],
            'nama_bulan' => ['type' => 'VARCHAR', 'constraint' => 20],
            'kuartal'    => ['type' => 'INT', 'constraint' => 1],
            'tahun'      => ['type' => 'INT', 'constraint' => 4],
        ]);
        $this->forge->addKey('sk_waktu', true);
        $this->forge->createTable('dim_waktu');
    }

    public function down()
    {
        $this->forge->dropTable('dim_waktu');
    }
}