<?php

namespace App\Controllers;

class AdminBerita extends BaseController
{
    // ==========================================
    // HALAMAN KELOLA BERITA
    // ==========================================
    public function index()
    {
        $session = session();
        $role = $session->get('role_admin');
        
        // 1. KUNCI PINTU: Hanya 'Editing' dan 'Sistem/Database' yang boleh masuk!
        if (!in_array($role, ['Editing', 'Sistem/Database'])) {
            $session->setFlashdata('error', 'Akses Ditolak! Anda tidak memiliki wewenang untuk mengelola Berita Publikasi.');
            return redirect()->to(base_url('admin-secret-panel'));
        }

        // 2. KONEKSI DATABASE
        $db = \Config\Database::connect();
        
        // 3. AMBIL DATA DARI TABEL 'berita'
        // Kita urutkan dari yang paling baru dibuat (DESC)
        $daftar_berita = $db->table('berita')->orderBy('created_at', 'DESC')->get()->getResultArray();
        
        // 4. HITUNG STATISTIK
        $total_berita = count($daftar_berita);

        // 5. SIAPKAN DATA UNTUK VIEW
        $data = [
            'title'         => 'Kelola Berita Publikasi | Astryveil',
            'role'          => $role,
            'nama_admin'    => $session->get('nama_admin'),
            'daftar_berita' => $daftar_berita,
            'total_berita'  => $total_berita
        ];

        // 6. TAMPILKAN KE VIEW
        return view('admin/berita', $data);
    }
}