<?php

namespace App\Controllers;

class AdminSecretPanel extends BaseController
{
    public function index()
    {
        if(session()->get('role') !== 'admin') return redirect()->to('/login')->with('error', 'Akses Ilegal!');
        $data = ['title' => 'Admin Panel | Astryveil Academy'];
        return view('dashboard/admin', $data);
    }
}