<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PegawaiPegawai extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id_pegawai' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_pegawai' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'alamat_pegawai' =>[
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],

            'id_kategori' =>[
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
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
        $this->forge->addKey('id_pegawai', true);
        $this->forge->addForeignKey('id_kategori','pegawai_kategori','id_kategori');
        $this->forge->createTable('pegawai_pegawai');
    }

    public function down()
    {
        //
        $this->forge->dropForeignKey('id_kategori','pegawai_kategori','id_kategori');

        $this->forge->dropTable('pegawai_pegawai');

    }
}
