<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table            = 'berita';
    protected $primaryKey       = 'id_berita';
    protected $allowedFields    = ['judul_berita', 'sub_judul', 'konten', 'penulis', 'link_url', 'created_at'];

    // Method khusus untuk API Pagination
    public function getPaginatedNews($limit, $offset)
    {
        return $this->select('berita.*, admin.user_id as id_penulis, admin.jenis_admin')
                    ->join('admin', 'admin.user_id = berita.penulis', 'left')
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit, $offset);
    }
}