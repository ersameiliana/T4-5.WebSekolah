<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\GuestModel;
use App\Models\AdminModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, cegah masuk ke halaman login lagi
        if (session()->get('logged_in')) {
            return redirect()->to(session()->get('redirect_url'));
        }

        $data = ['title' => 'Portal Login | Astryveil Academy'];
        return view('login', $data);
    }

    public function process()
    {
        $session = session();
        
        // Asumsi: Untuk pendaftar, 'userid' yang diinput di form login adalah Email
        $userid   = $this->request->getPost('userid'); 
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        $user = null;
        $dashboard_url = '';
        $user_name = '';
        $role_admin = null; // Variabel khusus untuk menangkap hak akses Admin

        // Pengecekan berdasarkan Role
        switch ($role) {
            case 'mahasiswa':
                $model = new MahasiswaModel();
                $user = $model->where('nim', $userid)->first();
                $dashboard_url = '/dashboard/mahasiswa';
                $user_name = $user['nama_mahasiswa'] ?? '';
                break;
            case 'dosen':
                $model = new DosenModel();
                $user = $model->where('nidn', $userid)->first();
                $dashboard_url = '/dashboard/dosen';
                $user_name = $user['nama_dosen'] ?? '';
                break;
            case 'guest':
                $model = new GuestModel();
                $user = $model->where('username', $userid)->first();
                $dashboard_url = '/dashboard/guest';
                $user_name = $user['nama_lengkap'] ?? '';

                if($user && $user['password'] === $password) {
                    $model->update($user['id_guest'], ['last_login' => date('Y-m-d H:i:s')]);
                }
                break;
            case 'admin':
                $model = new AdminModel();
                $user = $model->where('user_id', $userid)->first();
                $dashboard_url = '/admin-secret-panel';
                
                // 🔥 SESUAI SQL: Tabel admin tidak punya nama_lengkap, jadi kita jadikan user_id sebagai namanya
                $user_name = $user['user_id'] ?? 'Administrator';
                // 🔥 SESUAI SQL: Nama kolom untuk hak aksesnya adalah 'jenis_admin'
                $role_admin = $user['jenis_admin'] ?? null;
                break;
                
            // 👇 INI DIA JALUR MASUK BUAT CALON MAHASISWA BARU 👇
            case 'pendaftar':
                $db = \Config\Database::connect();
                // Cari pendaftar berdasarkan email
                $user = $db->table('pendaftar')->where('email', $userid)->get()->getRowArray();
                $dashboard_url = '/dashboard/pmb';
                $user_name = $user['nama_lengkap'] ?? '';
                break;

            default:
                $session->setFlashdata('error', 'Role tidak valid!');
                return redirect()->to('/login');
        }

        // ==========================================
        // SISTEM VALIDASI PASSWORD (PENTING!)
        // ==========================================
        $isPasswordValid = false;

        if ($role === 'pendaftar') {
            // Pendaftar menggunakan enkripsi password_hash()
            if ($user && password_verify($password, $user['password_hash'])) {
                $isPasswordValid = true;
            }
        } else {
            // Role kampus lain (termasuk Admin) menggunakan plain text
            if ($user && isset($user['password']) && $user['password'] === $password) {
                $isPasswordValid = true;
            }
        }

        // ==========================================
        // PENYIMPANAN SESSION (SUDAH FIX 100%)
        // ==========================================
        // Jika password dan user valid, persilakan masuk
        if ($isPasswordValid) {
            $ses_data = [
                'user_id'      => $userid,
                'id_user'      => $user['id'] ?? null, // Diperlukan agar Dashboard PMB bisa baca datanya
                'user_name'    => $user_name,
                
                // 🔥 VARIABEL KHUSUS AGAR DASHBOARD ADMIN BISA MEMBEDAKAN JABATAN 🔥
                'role_admin'   => $role_admin, // Isinya: 'Editing', 'Administrasi', atau 'Sistem/Database'
                'nama_admin'   => $user_name,  // Untuk ditampilkan di profil pojok kanan atas
                
                'role'         => $role,
                'logged_in'    => TRUE,
                'redirect_url' => $dashboard_url
            ];
            $session->set($ses_data);
            
            return redirect()->to($dashboard_url);
        } else {
            $session->setFlashdata('error', 'User ID / Email atau Password salah!');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // =======================================================
    // Method untuk menangani pendaftaran Guest / Orang Tua
    // =======================================================
    public function register()
    {
        $guestModel = new \App\Models\GuestModel();
        
        $jenis_akun = $this->request->getPost('jenis_akun');
        $nim_mahasiswa = $this->request->getPost('nim_mahasiswa');

        // Validasi: Jika wali, NIM Mahasiswa wajib valid (ada di database)
        if ($jenis_akun == 'Orang tua/Wali Mahasiswa') {
            $mhsModel = new \App\Models\MahasiswaModel();
            $cekMhs = $mhsModel->where('nim', $nim_mahasiswa)->first();
            
            if (!$cekMhs) {
                return redirect()->to('/login')->with('error', 'Pendaftaran Gagal: NIM Mahasiswa tidak ditemukan di sistem!');
            }
        } else {
            // Jika tamu umum, pastikan nim_mahasiswa null
            $nim_mahasiswa = null;
        }

        $data = [
            'jenis_akun'    => $jenis_akun,
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'username'      => $this->request->getPost('username'),
            'password'      => $this->request->getPost('password'), // Trigger SQL mu akan memvalidasi minimal 6 huruf&angka
            'nim_mahasiswa' => $nim_mahasiswa
        ];

        // Try-Catch agar error Trigger MySQL (kombinasi huruf & angka) bisa ditangkap dengan elegan
        try {
            $guestModel->insert($data);
            return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silakan Login menggunakan Username Anda.');
        } catch (\Exception $e) {
            // Menangkap pesan error dari Trigger SQL mu ('Password Guest minimal 6 karakter...')
            return redirect()->to('/login')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }


    // =======================================================
    // Method BARU untuk Pendaftaran Mahasiswa Baru (PMB)
    // =======================================================
    public function register_pmb()
    {
        $db = \Config\Database::connect();
        $session = session();

        // 1. Ambil data dari form pendaftaran PMB
        $nama         = $this->request->getPost('nama');
        $email        = $this->request->getPost('email');
        $telepon      = $this->request->getPost('telepon');
        $asal_sekolah = $this->request->getPost('asal_sekolah');
        $jalur        = $this->request->getPost('jalur');
        $prodi        = $this->request->getPost('prodi');
        $password     = $this->request->getPost('password');

        // 2. Cek apakah email sudah pernah dipakai mendaftar
        $existingUser = $db->table('pendaftar')->where('email', $email)->get()->getRowArray();
        if ($existingUser) {
            // Jika email sudah ada, kembalikan ke halaman daftar dengan pesan error
            $session->setFlashdata('error', 'Email ini sudah terdaftar! Silakan gunakan email lain atau login.');
            return redirect()->to('/daftar')->withInput();
        }

        // 3. Enkripsi (Hash) Password demi keamanan
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // 4. Siapkan Array Data untuk disimpan ke Database
        $dataInsert = [
            'nama_lengkap'      => $nama,
            'email'             => $email,
            'no_whatsapp'       => $telepon,
            'asal_sekolah'      => $asal_sekolah,
            'jalur_pendaftaran' => $jalur,
            'prodi_pilihan'     => $prodi,
            'password_hash'     => $password_hash,
            'status_pmb'        => 'registered' // Status awal
        ];

        // 5. Simpan ke tabel 'pendaftar'
        $db->table('pendaftar')->insert($dataInsert);
        
        // Ambil ID pendaftar yang baru saja dibuat
        $id_pendaftar = $db->insertID();

        // 6. Buat Sesi (Session) Login Otomatis
        $session->set([
            'id_user'   => $id_pendaftar,
            'nama'      => $nama,
            'email'     => $email,
            'role'      => 'pendaftar', // Role khusus untuk calon mahasiswa baru
            'logged_in' => true
        ]);

        // 7. Arahkan ke Dashboard PMB!
        return redirect()->to('/dashboard/pmb');
    }
}