<?php

namespace App\Controllers;

use App\Models\BeritaModel;

class Berita extends BaseController
{
    protected $beritaModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
    }

    // 1. Menampilkan Halaman Kerangka Utama
    public function index()
    {
        $data = [
            'title' => 'Portal Berita | Astryveil Academy'
        ];
        return view('berita', $data);
    }

    // 2. Endpoint API untuk Lazy Loading / Load More
    public function apiGetNews()
    {
        // Ambil parameter halaman dari request (default: 1)
        $page = $this->request->getVar('page') ?? 1;
        $limit = 6; // Ambil 6 berita per load
        $offset = ($page - 1) * $limit;

        $news = $this->beritaModel->getPaginatedNews($limit, $offset);

        // Bersihkan tag HTML dari konten untuk preview singkat
        foreach ($news as &$item) {
            // Potong teks agar tidak terlalu panjang di card
            $item['preview_konten'] = word_limiter(strip_tags($item['konten']), 20); 
            // Format tanggal
            $item['tanggal_format'] = date('d M Y', strtotime($item['created_at']));
        }

        return $this->response->setJSON($news);
    }
}