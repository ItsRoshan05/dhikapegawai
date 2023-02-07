<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\PegawaiModel;

class pegawai extends BaseController
{
    public function __construct(){
        $this->PegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $data = [
            "pegawai" => $this->PegawaiModel->allData(),
            "pegawaijoin" => $this->PegawaiModel->dataJoin()
        ];

        return view("client/pages/pegawai",$data);
    }

    public function tambah()
    {
        $data = [
            "kategori" => $this->PegawaiModel->allDataKategori()
        ];

        return view("client/pages/inputpegawai",$data);
    }

    public function save()
    {
        $this->PegawaiModel->save([
            "nama_pegawai" => $this->request->getVar("nama_pegawai"),
            "alamat_pegawai" => $this->request->getVar("alamat_pegawai"),
            "id_kategori" => $this->request->getVar("id_kategori")

        ]);
        return redirect()->to("/pegawai");
    }

    
    public function edit($id)
    {
        $data = [
            "kategori" => $this->PegawaiModel->allDataKategori(),
            "pegawai" =>$this->PegawaiModel->detailData($id)
        ];

        return view("client/pages/editpegawai",$data);
    }

    public function delete($id)
    {
        $data = [
            'id'=> $id
        ];
        $this->PegawaiModel->deleteData($data);
        return redirect()->to('/pegawai');
    }
}
