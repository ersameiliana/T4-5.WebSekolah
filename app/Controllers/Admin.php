<?php

namespace App\Controllers;

class Admin extends BaseController
{
    // ==========================================
    // HALAMAN KELOLA PROFIL WEB (TABEL HALAMAN)
    // ==========================================
    public function profil()
    {
        $session = session();
        $role = $session->get('role_admin');
        
        // 1. KUNCI PINTU: Hanya 'Editing' dan 'Sistem/Database' yang boleh masuk!
        if (!in_array($role, ['Editing', 'Sistem/Database'])) {
            $session->setFlashdata('error', 'Akses Ditolak! Anda tidak memiliki wewenang untuk mengelola Profil Web.');
            return redirect()->to(base_url('admin-secret-panel'));
        }

        // 2. KONEKSI DATABASE
        $db = \Config\Database::connect();
        
        // 3. AMBIL DATA DARI TABEL 'halaman'
        $daftar_halaman = $db->table('halaman')->orderBy('terakhir_diupdate', 'DESC')->get()->getResultArray();

        // 4. SIAPKAN DATA UNTUK VIEW
        $data = [
            'title'          => 'Kelola Profil Web & Halaman | Astryveil',
            'role'           => $role,
            'nama_admin'     => $session->get('nama_admin'),
            'daftar_halaman' => $daftar_halaman,
            'total_halaman'  => count($daftar_halaman)
        ];

        // 5. TAMPILKAN KE VIEW
        return view('admin/profil', $data);
    }

    // ==========================================
    // HALAMAN KELOLA PENGGUNA (ALL USERS)
    // ==========================================
    public function pengguna()
    {
        $session = session();
        $role = $session->get('role_admin');
        
        if (!in_array($role, ['Administrasi', 'Sistem/Database'])) {
            $session->setFlashdata('error', 'Akses Ditolak! Anda tidak memiliki wewenang.');
            return redirect()->to(base_url('admin-secret-panel'));
        }

        $db = \Config\Database::connect();
        
        $mahasiswa = $db->table('mahasiswa')->orderBy('nim', 'DESC')->get()->getResultArray();
        $dosen     = $db->table('dosen')->orderBy('nidn', 'DESC')->get()->getResultArray();
        $guest     = $db->table('guest')->orderBy('id_guest', 'DESC')->get()->getResultArray();
        $pendaftar = $db->table('pendaftar')->orderBy('id', 'DESC')->get()->getResultArray(); 
        
        $daftar_prodi = $db->table('prodi')->orderBy('fakultas', 'ASC')->orderBy('nama_prodi', 'ASC')->get()->getResultArray();
        
        $data = [
            'title'        => 'Kelola Pengguna | Astryveil',
            'role'         => $role,
            'nama_admin'   => $session->get('nama_admin'),
            'mahasiswa'    => $mahasiswa,
            'dosen'        => $dosen,
            'guest'        => $guest,
            'pendaftar'    => $pendaftar,
            'daftar_prodi' => $daftar_prodi,
            'stats'        => [
                'mhs'       => count($mahasiswa),
                'dosen'     => count($dosen),
                'guest'     => count($guest),
                'pendaftar' => count($pendaftar)
            ]
        ];

        return view('admin/pengguna', $data);
    }

    public function pendaftar_edit()
    {
        $role = session()->get('role_admin');
        if (!in_array($role, ['Administrasi', 'Sistem/Database'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses Ditolak!']);
        }

        $id = $this->request->getPost('id_pendaftar');
        
        $data = [
            'nama_lengkap'       => $this->request->getPost('nama_lengkap'),
            'email'              => $this->request->getPost('email'),
            'asal_sekolah'       => $this->request->getPost('asal_sekolah'),
            'prodi_pilihan'      => $this->request->getPost('prodi_pilihan'),
            'status_pendaftaran' => $this->request->getPost('status_pendaftaran')
        ];

        $db = \Config\Database::connect();
        $db->table('pendaftar')->where('id', $id)->update($data);
        
        $data['id'] = $id;

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Status pendaftaran berhasil diperbarui!', 
            'data' => $data
        ]);
    }

    public function pendaftar_delete($id)
    {
        $role = session()->get('role_admin');
        if ($role !== 'Sistem/Database') {
            return $this->response->setJSON(['success' => false, 'message' => 'Hanya Superadmin yang berhak menghapus data!']);
        }

        $db = \Config\Database::connect();
        $db->table('pendaftar')->where('id', $id)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Akun pendaftar berhasil dihapus permanen.']);
    }

    // ==========================================
    // FUNGSI TAMBAH AKUN PENGGUNA (FIXED)
    // ==========================================
    public function mahasiswa_add()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getPost();
        
        $data['nim'] = 0; 
        unset($data['nim_lama']); 

        if (empty($data['password'])) {
            $data['password'] = ''; 
        }

        try {
            $db->table('mahasiswa')->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Akun Mahasiswa berhasil didaftarkan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function dosen_add()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getPost();
        
        unset($data['nidn_lama']);

        if (empty($data['password'])) {
            $data['password'] = '';
        }

        try {
            $db->table('dosen')->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Akun Dosen berhasil didaftarkan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function guest_add()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getPost();
        
        unset($data['id_guest']);

        if ($data['jenis_akun'] == 'Tamu umum' || empty($data['nim_mahasiswa'])) {
            $data['nim_mahasiswa'] = null;
        }

        try {
            $db->table('guest')->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Akun Guest/Wali berhasil didaftarkan!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ==========================================
    // FUNGSI EDIT AKUN PENGGUNA
    // ==========================================
    public function mahasiswa_edit()
    {
        $db = \Config\Database::connect();
        $nim_lama = $this->request->getPost('nim_lama'); 
        $data = $this->request->getPost();
        unset($data['nim_lama']); 

        if (empty($data['password'])) {
            unset($data['password']); 
        }

        try {
            $db->table('mahasiswa')->where('nim', $nim_lama)->update($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Data Mahasiswa berhasil diperbarui!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function dosen_edit()
    {
        $db = \Config\Database::connect();
        $nidn_lama = $this->request->getPost('nidn_lama');
        $data = $this->request->getPost();
        unset($data['nidn_lama']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        try {
            $db->table('dosen')->where('nidn', $nidn_lama)->update($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Data Dosen berhasil diperbarui!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function guest_edit()
    {
        $db = \Config\Database::connect();
        $id_guest = $this->request->getPost('id_guest');
        $data = $this->request->getPost();
        unset($data['id_guest']);

        if ($data['jenis_akun'] == 'Tamu umum' || empty($data['nim_mahasiswa'])) {
            $data['nim_mahasiswa'] = null;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        try {
            $db->table('guest')->where('id_guest', $id_guest)->update($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Data Guest berhasil diperbarui!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ==========================================
    // HALAMAN KELOLA JURUSAN / PRODI
    // ==========================================
    public function jurusan()
    {
        $session = session();
        $role = $session->get('role_admin');
        
        if (!in_array($role, ['Administrasi', 'Sistem/Database'])) {
            $session->setFlashdata('error', 'Akses Ditolak! Anda tidak memiliki wewenang untuk mengelola Jurusan.');
            return redirect()->to(base_url('admin-secret-panel'));
        }

        $db = \Config\Database::connect();
        
        $daftar_prodi = $db->table('prodi')->orderBy('fakultas', 'ASC')->get()->getResultArray();
        $total_fakultas = $db->table('prodi')->select('fakultas')->distinct()->countAllResults();
        $total_prodi = count($daftar_prodi);

        $data = [
            'title'          => 'Kelola Jurusan & Prodi | Astryveil',
            'role'           => $role,
            'nama_admin'     => $session->get('nama_admin'),
            'daftar_prodi'   => $daftar_prodi,
            'total_fakultas' => $total_fakultas,
            'total_prodi'    => $total_prodi
        ];

        return view('admin/jurusan', $data);
    }

    public function jurusan_add()
    {
        $role = session()->get('role_admin');
        if (!in_array($role, ['Administrasi', 'Sistem/Database'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses Ditolak!']);
        }

        $data = [
            'fakultas'   => $this->request->getPost('fakultas'),
            'nama_prodi' => $this->request->getPost('nama_prodi'),
            'strata'     => $this->request->getPost('strata')
        ];

        $db = \Config\Database::connect();
        $db->table('prodi')->insert($data);
        
        $data['id'] = $db->insertID(); 

        return $this->response->setJSON(['success' => true, 'message' => 'Program Studi berhasil ditambahkan!', 'data' => $data]);
    }

    public function jurusan_edit()
    {
        $role = session()->get('role_admin');
        if (!in_array($role, ['Administrasi', 'Sistem/Database'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Akses Ditolak!']);
        }

        $id = $this->request->getPost('id_prodi');
        $data = [
            'fakultas'   => $this->request->getPost('fakultas'),
            'nama_prodi' => $this->request->getPost('nama_prodi'),
            'strata'     => $this->request->getPost('strata')
        ];

        $db = \Config\Database::connect();
        $db->table('prodi')->where('id', $id)->update($data);
        
        $data['id'] = $id;

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil diperbarui!', 'data' => $data]);
    }

    public function jurusan_delete($id)
    {
        $role = session()->get('role_admin');
        if ($role !== 'Sistem/Database') {
            return $this->response->setJSON(['success' => false, 'message' => 'Hanya Superadmin yang bisa menghapus data!']);
        }

        $db = \Config\Database::connect();
        $db->table('prodi')->where('id', $id)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil dihapus permanen!']);
    }

    // ==========================================
    // HALAMAN OTORISASI & SYSTEM LOGS
    // ==========================================
    public function otorisasi()
    {
        $session = session();
        $role = $session->get('role_admin');
        
        if ($role !== 'Sistem/Database') {
            $session->setFlashdata('error', 'Akses Ditolak! Hanya Superadmin yang diizinkan masuk ke menu Otorisasi.');
            return redirect()->to(base_url('admin-secret-panel'));
        }

        $db = \Config\Database::connect();
        $daftar_admin = $db->table('admin')->get()->getResultArray();

        $data = [
            'title'        => 'Otorisasi & Hak Akses | Astryveil',
            'role'         => $role,
            'nama_admin'   => $session->get('nama_admin'),
            'daftar_admin' => $daftar_admin
        ];

        return view('admin/otorisasi', $data);
    }

    public function system_logs()
    {
        $session = session();
        $role = $session->get('role_admin');
        
        if ($role !== 'Sistem/Database') {
            $session->setFlashdata('error', 'Akses Ditolak! Hanya Superadmin yang dapat melihat System Logs.');
            return redirect()->to(base_url('admin-secret-panel'));
        }
        
        $db = \Config\Database::connect();
        
        // Asumsi: Anda memiliki tabel bernama 'system_logs' (atau sesuaikan dengan nama tabel log Anda)
        // Diurutkan dari aktivitas terbaru
        $daftar_log = $db->table('system_logs')->orderBy('id', 'DESC')->get()->getResultArray();

        $data = [
            'title'      => 'Database System Logs | Astryveil',
            'role'       => $role,
            'nama_admin' => $session->get('nama_admin'),
            'daftar_log' => $daftar_log
        ];

        return view('admin/system_logs', $data);
    }

}