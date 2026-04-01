<?php
namespace App\Controllers;
use App\Models\BeritaModel;

class Api extends BaseController
{
    public function getNews()
    {
        $model = new BeritaModel();
        $page = $this->request->getVar('page') ?? 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;

        // Mengambil data secara bertahap (Pagination API)
        $berita = $model->orderBy('created_at', 'DESC')->findAll($limit, $offset);
        
        return $this->response->setJSON($berita);
    }
}