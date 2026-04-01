<?php

namespace App\Controllers;

use App\Models\StrukturOrganisasiModel;
use App\Models\StrukturPimpinanModel;

class Profil extends BaseController
{
    protected $strukturOrgModel;
    protected $strukturPimModel;

    public function __construct()
    {
        $this->strukturOrgModel = new StrukturOrganisasiModel();
        $this->strukturPimModel = new StrukturPimpinanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Profil | Astryveil Academy',
            // Kita ambil data dari database
            'struktur_organisasi' => $this->strukturOrgModel->findAll(),
            'struktur_pimpinan'   => $this->strukturPimModel->getPimpinan()
        ];

        return view('profil', $data);
    }
}