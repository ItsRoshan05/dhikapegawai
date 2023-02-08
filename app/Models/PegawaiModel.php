<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table = 'pegawai_pegawai';
    
    protected $useTimeStamps = true;
    protected $allowedFields = ['nama_pegawai','alamat_pegawai','id_kategori'];

    public function allData()
    {
        return $this->Get()->getResultArray();
    }

    public function dataJoin()
    {
                // Mendapatkan hasil join dari dua tabel
                // $query = $db->table('table1')
                // ->join('table2', 'table1.id = table2.table1_id')
                // ->get();

        // Mengembalikan hasil join sebagai array
        // return $query->getResultArray();
        
        return $this->select('pegawai_pegawai.*, pegawai_kategori.nama_kategori, pegawai_kategori.jabatan_kategori')
                ->join('pegawai_kategori', 'pegawai_pegawai.id_kategori = pegawai_kategori.id')
                ->get()
                ->getResultArray();
    }

    public function allDataKategori()
    {
        return $this->db->table('pegawai_kategori')->Get()->getResultArray();
    }

    public function detailData($id)
    {
        return $this->where('id', $id)->Get()->getRow();
    }

    public function deleteData($data)
    {
        $this->where('id', $data['id'])
            ->delete($data);
    }

    public function updateData($data)
    {
        $this->where('id', $data['id'])
            ->update($data);
    }
}