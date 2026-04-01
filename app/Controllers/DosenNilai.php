<?php

namespace App\Controllers;

use App\Models\NilaiModel;
use App\Models\KelasKuliahModel;
use App\Models\MahasiswaModel;

class DosenNilai extends BaseController
{
    protected $nilaiModel;
    protected $kelasModel;
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->nilaiModel = new NilaiModel();
        $this->kelasModel = new KelasKuliahModel();
        $this->mahasiswaModel = new MahasiswaModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'dosen') return redirect()->to('/login');

        $nidn = session()->get('user_id');

        $data = [
            'title'        => 'Input Nilai Mahasiswa | Astryveil',
            'kelas_dosen'  => $this->kelasModel->getKelasDosen($nidn),
            'riwayat_nilai'=> $this->nilaiModel->getRiwayatNilai($nidn)
        ];

        return view('dosen/input_nilai', $data);
    }

    public function store()
    {
        if (session()->get('role') !== 'dosen') return redirect()->to('/login');

        $nidn = session()->get('user_id');
        $nim = $this->request->getPost('nim');
        $id_kelas_mk = $this->request->getPost('kelas_mk'); // Isinya kombinasi: id_kelas|id_mk
        $nilai_angka = $this->request->getPost('nilai_angka');

        // Validasi apakah NIM terdaftar
        $cek_mhs = $this->mahasiswaModel->find($nim);
        if(!$cek_mhs) {
            return redirect()->to('/dosen/nilai')->with('error', 'NIM Mahasiswa tidak ditemukan di sistem!');
        }

        // Pecah value id_kelas dan id_mk
        list($id_kelas, $id_mk) = explode('|', $id_kelas_mk);

        // --- LOGIKA PERHITUNGAN NILAI HURUF ---
        $nilai_huruf = 'E';
        if ($nilai_angka >= 85) { $nilai_huruf = 'A'; }
        elseif ($nilai_angka >= 70) { $nilai_huruf = 'B'; }
        elseif ($nilai_angka >= 55) { $nilai_huruf = 'C'; }
        elseif ($nilai_angka >= 40) { $nilai_huruf = 'D'; }

        // Simpan ke database
        $this->nilaiModel->insert([
            'nim'         => $nim,
            'id_mk'       => $id_mk,
            'id_kelas'    => $id_kelas,
            'nidn'        => $nidn,
            'nilai_angka' => $nilai_angka,
            'nilai_huruf' => $nilai_huruf
        ]);

        return redirect()->to('/dosen/nilai')->with('success', "Nilai untuk mahasiswa {$cek_mhs['nama_mahasiswa']} (NIM: $nim) berhasil disimpan dengan predikat $nilai_huruf.");
    }
}