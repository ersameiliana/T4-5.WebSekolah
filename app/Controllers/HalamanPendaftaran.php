<?php

namespace App\Controllers;

class HalamanPendaftaran extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Penerimaan Mahasiswa Baru | Astryveil Academy'
        ];
        return view('pendaftaran', $data);
    }

    public function daftar()
    {
        // Panggil database CodeIgniter
        $db = \Config\Database::connect();
        
        // Tarik data prodi dari database, urutkan berdasarkan Fakultas
        $prodi_list = $db->table('prodi')->orderBy('fakultas', 'ASC')->get()->getResultArray();

        $data = [
            'title'      => 'Formulir Pendaftaran | Astryveil Academy',
            'prodi_list' => $prodi_list // Kirim array prodi ke View
        ];
        
        return view('daftar', $data);
    }
}