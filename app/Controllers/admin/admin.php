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
        return view('client/index');
    }
}
