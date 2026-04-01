<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function mahasiswa()
    {
        if(session()->get('role') !== 'mahasiswa') return redirect()->to('/login')->with('error', 'Akses Ilegal!');
        $data = ['title' => 'Dashboard Mahasiswa | Astryveil Academy'];
        return view('dashboard/mahasiswa', $data);
    }

    public function dosen()
    {
        if(session()->get('role') !== 'dosen') return redirect()->to('/login')->with('error', 'Akses Ilegal!');
        $data = ['title' => 'Dashboard Dosen | Astryveil Academy'];
        return view('dashboard/dosen', $data);
    }

    public function guest()
    {
        if(session()->get('role') !== 'guest') return redirect()->to('/login')->with('error', 'Akses Ilegal!');
        $data = ['title' => 'Dashboard Guest | Astryveil Academy'];
        return view('dashboard/guest', $data);
    }
    public function pmb()
    {
        $session = session();
        
        // Proteksi: Pastikan hanya role 'pendaftar' yang bisa masuk sini
        if ($session->get('role') !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        // Ambil data asli pendaftar dari tabel 'pendaftar'
        $user_data = $db->table('pendaftar')->where('id', $session->get('id_user'))->get()->getRowArray();

        $data = [
            'title' => 'Dashboard PMB | Astryveil Academy',
            'user'  => $user_data // Kirim data asli ke View
        ];

        return view('dashboard_pmb', $data);
    }
    // ==========================================
    // TAMPILKAN FORM BIODATA PMB
    // ==========================================
    public function biodata()
    {
        $session = session();
        if ($session->get('role') !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $user = $db->table('pendaftar')->where('id', $session->get('id_user'))->get()->getRowArray();

        // Jika status sudah lewat tahap 1, cegah user mengisi ulang biodata
        if ($user['status_pmb'] !== 'registered') {
            return redirect()->to('/dashboard/pmb');
        }

        $data = [
            'title' => 'Lengkapi Biodata | Astryveil Academy',
            'user'  => $user
        ];

        return view('biodata_pmb', $data);
    }

    // ==========================================
    // SIMPAN BIODATA & UPDATE STATUS PMB
    // ==========================================
    public function store_biodata()
    {
        $session = session();
        if ($session->get('role') !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $id_user = $session->get('id_user');

        // Ambil data dari form
        $updateData = [
            'nik'            => $this->request->getPost('nik'),
            'tempat_lahir'   => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'  => $this->request->getPost('tanggal_lahir'),
            'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
            'nama_ortu'      => $this->request->getPost('nama_ortu'),
            'status_pmb'     => 'biodata_complete' // 🔥 INI KUNCINYA! Status di-upgrade!
        ];

        // Update tabel pendaftar
        $db->table('pendaftar')->where('id', $id_user)->update($updateData);

        // Lempar kembali ke Dashboard PMB
        return redirect()->to('/dashboard/pmb');
    }
// ==========================================
    // 1. TAMPILKAN FORM UPLOAD BERKAS (Fungsi yang hilang tadi!)
    // ==========================================
    public function upload()
    {
        $session = session();
        if ($session->get('role') !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $user = $db->table('pendaftar')->where('id', $session->get('id_user'))->get()->getRowArray();

        // Cegah bypass: Jika belum isi biodata, kembalikan ke dashboard
        if ($user['status_pmb'] === 'registered') {
            return redirect()->to('/dashboard/pmb');
        }

        $data = [
            'title' => 'Unggah Berkas | Astryveil Academy',
            'user'  => $user
        ];

        return view('upload_berkas_pmb', $data);
    }

    // ==========================================
    // 2. PROSES UPLOAD (VERSI DETEKTIF ERROR) 🕵️‍♂️
    // ==========================================
    public function store_upload()
    {
        $session = session();
        $db = \Config\Database::connect();
        $id_user = $session->get('id_user');

        // 1. Cek Sesi
        if (!$id_user) {
            die("<h1>ERROR: ID User hilang dari session! Coba logout lalu login lagi.</h1>");
        }

        // 2. Cek atau Buat Folder Paksa
        $targetPath = FCPATH . 'uploads/pmb/';
        if (!is_dir($targetPath)) {
            if (!mkdir($targetPath, 0777, true)) {
                die("<h1>ERROR: Gagal membuat folder di $targetPath. Cek izin akses foldermu!</h1>");
            }
        }

        // 3. Ambil File
        $foto  = $this->request->getFile('berkas_foto');
        $kk    = $this->request->getFile('berkas_kk');
        $rapor = $this->request->getFile('berkas_rapor');

        $namaFoto  = null;
        $namaKk    = null;
        $namaRapor = null;

        // 4. Pindahkan File
        try {
            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $namaFoto = $foto->getRandomName();
                $foto->move($targetPath, $namaFoto);
            }
            if ($kk && $kk->isValid() && !$kk->hasMoved()) {
                $namaKk = $kk->getRandomName();
                $kk->move($targetPath, $namaKk);
            }
            if ($rapor && $rapor->isValid() && !$rapor->hasMoved()) {
                $namaRapor = $rapor->getRandomName();
                $rapor->move($targetPath, $namaRapor);
            }
        } catch (\Exception $e) {
            die("<h1>ERROR SAAT MEMINDAHKAN FILE:</h1><p>" . $e->getMessage() . "</p>");
        }

        // 5. Simpan ke Database
        $updateData = [
            'berkas_foto'  => $namaFoto,
            'berkas_kk'    => $namaKk,
            'berkas_rapor' => $namaRapor,
            'status_pmb'   => 'document_uploaded' // 🔥 Paksa naik level!
        ];

        $update = $db->table('pendaftar')->where('id', $id_user)->update($updateData);

        // 6. Jika Database Menolak
        if (!$update) {
            echo "<h1>DATABASE MYSQL MENOLAK DATA!</h1>";
            echo "<pre>";
            print_r($db->error());
            echo "</pre>";
            die();
        }

        // Jika semua lancar, kembali ke dashboard
        $session->setFlashdata('success', 'Berkas berhasil diunggah!');
        return redirect()->to('/dashboard/pmb');
    }
    // ==========================================
    // 1. TAMPILKAN HALAMAN SIMULASI PEMBAYARAN
    // ==========================================
    public function bayar()
    {
        $session = session();
        if ($session->get('role') !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $user = $db->table('pendaftar')->where('id', $session->get('id_user'))->get()->getRowArray();

        // Cegah bypass: Harus sudah upload dokumen baru bisa bayar
        if ($user['status_pmb'] !== 'document_uploaded') {
            return redirect()->to('/dashboard/pmb');
        }

        $data = [
            'title' => 'Pembayaran PMB | Astryveil Academy',
            'user'  => $user
        ];
        
        return view('bayar_pmb', $data);
    }

    // ==========================================
    // 2. PROSES BAYAR & UPGRADE STATUS 
    // ==========================================
    public function proses_bayar()
    {
        $session = session();
        if ($session->get('role') !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        
        // 🔥 Ubah jadi 'verified' agar langsung melompat ke tahap seleksi (Step 5)
        $db->table('pendaftar')->where('id', $session->get('id_user'))->update([
            'status_pmb' => 'verified' 
        ]);

        $session->setFlashdata('success', 'Pembayaran berhasil dikonfirmasi secara otomatis! Silakan lanjut ke tahap selanjutnya.');
        
        return redirect()->to('/dashboard/pmb');
    }
    // ==========================================
    // 1. TAMPILKAN RUANG UJIAN CBT
    // ==========================================
    public function cbt()
    {
        $session = session();
        if ($session->get('role') !== 'pendaftar') return redirect()->to('/login');

        $db = \Config\Database::connect();
        $user = $db->table('pendaftar')->where('id', $session->get('id_user'))->get()->getRowArray();

        // Keamanan: Hanya yang sudah bayar (verified) dan jalur Reguler yang boleh ujian
        if ($user['status_pmb'] !== 'verified' || $user['jalur_pendaftaran'] !== 'Reguler') {
            return redirect()->to('/dashboard/pmb');
        }

        $data = [
            'title' => 'Ujian Seleksi CBT | Astryveil Academy',
            'user'  => $user
        ];
        
        return view('cbt_pmb', $data);
    }

    // ==========================================
    // 2. PROSES HASIL UJIAN & NAIK STATUS
    // ==========================================
    public function proses_cbt()
    {
        $session = session();
        $db = \Config\Database::connect();
        
        // 🔥 Ubah status menjadi 'test_done' (Ujian Selesai)
        $db->table('pendaftar')->where('id', $session->get('id_user'))->update([
            'status_pmb' => 'test_done' 
        ]);

        $session->setFlashdata('success', 'Ujian CBT berhasil diselesaikan! Silakan tunggu pengumuman kelulusan.');
        return redirect()->to('/dashboard/pmb');
    }
}