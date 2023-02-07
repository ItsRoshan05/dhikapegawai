<?php

namespace App\Controllers;
// use App\Models\PegawaiModel;



class Pegawai extends BaseController
{
    public function __construct()
    {
        // $this->pegawaimodel = new PegawaiModel();
        $this->req = \Config\Services::request();
        $this->masterModel = new \App\Models\MasterModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $datapegawai = $this->pegawaimodel->findall();
        $data = [
            'pegawai' => $datapegawai
        ];

        return view('client/index');
    }

    public function data()
    {

    }
}
