<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PegawaiKategori extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id_kategori' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'jabatan_kategori' =>[
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' =>[
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_kategori', true);
        $this->forge->createTable('pegawai_kategori');
    }

    public function down()
    {
        //
        $this->forge->dropTable('pegawai_kategori');
    }
}
