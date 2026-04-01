<?php

namespace App\Controllers;

use App\Models\StrukturPimpinanModel;
use App\Models\DosenModel;
use App\Models\MataKuliahModel;

class Home extends BaseController
{
    public function index()
    {
        $data = ['title' => 'Astryveil Academy | Starlight Wisdom'];
        return view('home', $data);
    }

    public function akademik()
    {
        $pimpinanModel = new StrukturPimpinanModel();
        $dosenModel    = new DosenModel();
        $matkulModel   = new MataKuliahModel();
        
        $data = [
            'title'    => 'Program Akademik | Astryveil Academy',
            'pimpinan' => $pimpinanModel->getPimpinan(),
            'dosen'    => $dosenModel->findAll(), // Ambil semua Dosen
            'matkul'   => $matkulModel->findAll() // Ambil semua Mata Kuliah
        ];
        
        return view('akademik', $data);
    }
    public function tentangkami()
    {
        $data = [
            'title' => 'Tentang Kami | Astryveil Academy'
        ];
        return view('tentangkami', $data);
    }
}