<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- AREA PUBLIK (Bebas Diakses Tanpa Login) ---
$routes->get('/', 'Home::index');
$routes->get('/akademik', 'Home::akademik');
$routes->get('/profil', 'Profil::index');
$routes->get('/berita', 'Berita::index');
$routes->get('/berita/apiGetNews', 'Berita::apiGetNews');

// 👇 RUTE YANG BARU SAJA DISELAMATKAN DARI SATPAM 👇
$routes->get('/fakultas', 'Fakultas::index');
$routes->get('/fakultas/(:any)', 'Fakultas::detail/$1');
$routes->get('/tentang-kami', 'Home::tentangkami');
$routes->get('/pendaftaran', 'HalamanPendaftaran::index');
$routes->get('/daftar', 'HalamanPendaftaran::daftar');

// --- AREA AUTH & LOGIN ---
$routes->get('/login', 'Auth::index');
$routes->post('/auth/process', 'Auth::process');
$routes->get('/auth/logout', 'Auth::logout');

// 👇 PENDAFTARAN GUEST & PMB 👇
$routes->post('/auth/register', 'Auth::register');
$routes->post('/auth/register_pmb', 'Auth::register_pmb'); // <--- INI DIA PINTU YANG BARU DIBUKA! 🚀


// --- AREA PRIVATE (Wajib Login & Punya Sesi) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard Masing-masing Role
    $routes->get('/dashboard/mahasiswa', 'Dashboard::mahasiswa');
    $routes->get('/dashboard/dosen', 'Dashboard::dosen');
    $routes->get('/dashboard/guest', 'Dashboard::guest');

    $routes->get('/dashboard/pmb', 'Dashboard::pmb');

    $routes->get('/pmb/biodata', 'Dashboard::biodata');
    $routes->post('/pmb/biodata/store', 'Dashboard::store_biodata');

    $routes->get('/pmb/upload', 'Dashboard::upload');
    $routes->post('/pmb/upload/store', 'Dashboard::store_upload');

    $routes->get('/pmb/bayar', 'Dashboard::bayar');
    $routes->post('/pmb/bayar/proses', 'Dashboard::proses_bayar');

    $routes->get('/pmb/cbt', 'Dashboard::cbt');
    $routes->post('/pmb/cbt/proses', 'Dashboard::proses_cbt');
    
    // Admin Secret Panel
    $routes->get('/admin-secret-panel', 'AdminSecretPanel::index');
    
    // CRUD Berita Admin
    $routes->get('/admin/berita', 'AdminBerita::index');
    $routes->get('/admin/berita/create', 'AdminBerita::create');
    $routes->post('/admin/berita/store', 'AdminBerita::store');
    $routes->get('/admin/berita/delete/(:num)', 'AdminBerita::delete/$1');
    
    // CRUD Nilai Dosen
    $routes->get('/dosen/nilai', 'DosenNilai::index');
    $routes->post('/dosen/nilai/store', 'DosenNilai::store');
    
});
// ==========================================
// ROUTE PORTAL MAHASISWA (AKADEMIK)
// ==========================================
$routes->group('mahasiswa', function($routes) {
    // Mengarah ke Controller "Akademik", fungsi "dashboard"
    $routes->get('dashboard', 'Akademik::dashboard'); 
    
    // Mengarah ke Controller "Akademik", fungsi "jadwal"
    $routes->get('jadwal', 'Akademik::jadwal');       
    
    // Mengarah ke Controller "Akademik", fungsi "khs"
    $routes->get('khs', 'Akademik::khs');             
    
    // Mengarah ke Controller "Akademik", fungsi "materi"
    $routes->get('materi', 'Akademik::materi');       
});
// ==========================================
// ROUTE KHUSUS ADMIN PANEL
// ==========================================
$routes->group('admin', function($routes) {
    $routes->get('profil', 'Admin::profil');
    $routes->get('berita', 'AdminBerita::index');

    // 👇 TAMBAHKAN DUA ROUTE INI 👇
    $routes->get('otorisasi', 'Admin::otorisasi');
    $routes->get('system-logs', 'Admin::system_logs'); 
    
    // Tampilkan Halaman Kelola Pengguna
    $routes->get('pengguna', 'Admin::pengguna');

    // Tampilkan Halaman Kelola Pengguna
    $routes->get('pengguna', 'Admin::pengguna');

    // 🔥 ROUTE CRUD PENGGUNA: TAMBAH AKUN BARU 🔥
    $routes->post('pengguna/mahasiswa/add', 'Admin::mahasiswa_add');
    $routes->post('pengguna/dosen/add', 'Admin::dosen_add');
    $routes->post('pengguna/guest/add', 'Admin::guest_add');

    // 🔥 ROUTE CRUD PENGGUNA: PENDAFTAR / PMB 🔥
    $routes->post('pengguna/pendaftar/edit', 'Admin::pendaftar_edit');
    $routes->delete('pengguna/pendaftar/(:num)', 'Admin::pendaftar_delete/$1');

    // 🔥 ROUTE CRUD PENGGUNA: TAMBAH AKUN BARU 🔥
    $routes->post('pengguna/mahasiswa/add', 'Admin::mahasiswa_add');
    $routes->post('pengguna/dosen/add', 'Admin::dosen_add');
    $routes->post('pengguna/guest/add', 'Admin::guest_add');

    // 👇 TAMBAHKAN ROUTE EDIT INI 👇
    $routes->post('pengguna/mahasiswa/edit', 'Admin::mahasiswa_edit');
    $routes->post('pengguna/dosen/edit', 'Admin::dosen_edit');
    $routes->post('pengguna/guest/edit', 'Admin::guest_edit');

    // 🔥 ROUTE CRUD JURUSAN (MODERN RESTful) 🔥
    $routes->get('jurusan', 'Admin::jurusan');
    $routes->post('jurusan/add', 'Admin::jurusan_add');
    $routes->post('jurusan/edit', 'Admin::jurusan_edit');
    $routes->delete('jurusan/(:num)', 'Admin::jurusan_delete/$1');
});