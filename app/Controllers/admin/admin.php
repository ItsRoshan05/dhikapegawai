<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\PegawaiModel;

class admin extends BaseController
{
    public function __construct()
    {
        $this->pegawaimodel = new PegawaiModel();
    }

    public function index()
    {
        $data=[
            'countpegawai' => $this->pegawaimodel->countAll()
        ];

        return view('client/index', $data);
    }
}
