<?php

namespace App\Controllers;

use App\Models\BeritaModel;

class AdminBerita extends BaseController
{
    protected $beritaModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
    }

    // Menampilkan daftar berita
    public function index()
    {
        // Pastikan hanya admin yang bisa akses
        if (session()->get('role') !== 'admin') return redirect()->to('/login');

        $data = [
            'title'  => 'Manajemen Berita | Admin Panel',
            'berita' => $this->beritaModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('admin/berita/index', $data);
    }

    // Menampilkan form tambah
    public function create()
    {
        if (session()->get('role') !== 'admin') return redirect()->to('/login');
        $data = ['title' => 'Tulis Berita Baru | Admin Panel'];
        return view('admin/berita/form', $data);
    }

    // Menyimpan data berita baru ke Database
    public function store()
    {
        // Ambil ID Penulis dari Session Login
        $penulis_id = session()->get('user_id');

        $data = [
            'judul_berita' => $this->request->getPost('judul_berita'),
            'sub_judul'    => $this->request->getPost('sub_judul'),
            'konten'       => $this->request->getPost('konten'),
            'penulis'      => $penulis_id,
            // link_url sengaja dikosongkan agar Trigger SQL 'trg_berita_slug_bi' yang bekerja otomatis!
        ];

        $this->beritaModel->insert($data);
        return redirect()->to('/admin/berita')->with('success', 'Berita berhasil diterbitkan! Halaman AI juga telah di-generate oleh Trigger SQL.');
    }

    // Menghapus berita
    public function delete($id)
    {
        $this->beritaModel->delete($id);
        return redirect()->to('/admin/berita')->with('success', 'Berita berhasil dihapus.');
    }
}