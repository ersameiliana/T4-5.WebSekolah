<?php

namespace App\Controllers;

class Akademik extends BaseController
{
    // ==========================================
    // 1. HALAMAN DASHBOARD UTAMA
    // ==========================================
    public function dashboard()
    {
        // Nanti ganti ini dengan query database sungguhan
        $data = [
            'mahasiswa' => [
                'total_sks'       => 144,
                'ipk'             => '3.85',
                'status_akademik' => 'Aktif'
            ],
            'jadwal_hari_ini' => [
                ['jam_mulai' => '08:00', 'jam_selesai' => '10:30', 'ruangan' => 'Ruang A.201 - Gedung Pusat', 'mata_kuliah' => 'Pemrograman Web Lanjut', 'sks' => 3, 'dosen' => 'Dr. Marcus Elvarion, Ph.D.'],
                ['jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'ruangan' => 'Ruang B.104 - Gedung Sains', 'mata_kuliah' => 'Kecerdasan Buatan', 'sks' => 3, 'dosen' => 'Prof. Dr. Adrian Valerian, M.Sc.']
            ],
            'data_khs' => [
                ['mata_kuliah' => 'Sistem Basis Data', 'kode_mk' => 'TIK201', 'sks' => 3, 'nilai_huruf' => 'A'],
                ['mata_kuliah' => 'Kalkulus Lanjut', 'kode_mk' => 'MAT102', 'sks' => 3, 'nilai_huruf' => 'B'],
                ['mata_kuliah' => 'Fisika Dasar', 'kode_mk' => 'FIS101', 'sks' => 2, 'nilai_huruf' => 'E'], // Contoh tidak lulus
            ]
        ];

        // Pastikan nama view ini sesuai dengan file dashboard kamu sebelumnya
        return view('dashboard_akademik', $data); 
    }

    // ==========================================
    // 2. HALAMAN JADWAL KULIAH FULL
    // ==========================================
    public function jadwal()
    {
        $data = [
            // Dummy data jadwal untuk ditampilkan di view
            'jadwal_mingguan' => [
                'Senin' => [
                    ['jam' => '08:00 - 10:30', 'mk' => 'Pemrograman Web Lanjut', 'ruang' => 'Ruang A.201']
                ],
                'Selasa' => [
                    ['jam' => '10:00 - 12:00', 'mk' => 'Jaringan Komputer', 'ruang' => 'Lab Komputer 1']
                ]
            ]
        ];

        return view('jadwal_kuliah', $data);
    }

    // ==========================================
    // 3. HALAMAN KHS (KARTU HASIL STUDI)
    // ==========================================
    public function khs()
    {
        return view('khs_mahasiswa');
    }

    // ==========================================
    // 4. HALAMAN MATERI & TUGAS
    // ==========================================
    public function materi()
    {
        return view('materi_tugas');
    }
}