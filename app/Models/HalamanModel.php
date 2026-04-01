<?php

namespace App\Models;

use CodeIgniter\Model;

class HalamanModel extends Model
{
    protected $table            = 'halaman';
    protected $primaryKey       = 'id_halaman';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_halaman', 'slug_url', 'konten_halaman', 'admin_editor'];

    // Get profil page specifically
    public function getProfil()
    {
        return $this->where('slug_url', 'profil')->first();
    }

    // Get all for admin list
    public function getAllProfil()
    {
        return $this->where('slug_url LIKE', '%profil%')->orderBy('terakhir_diupdate', 'DESC')->findAll();
    }

    // Ensure single profil page exists
    public function ensureProfilExists()
    {
        $profil = $this->getProfil();
        if (!$profil) {
            $this->insert([
                'nama_halaman' => 'Profil Universitas',
                'slug_url' => 'profil',
                'konten_halaman' => '<p>Halaman profil belum diisi. Gunakan panel admin untuk mengelola konten.</p>',
                'admin_editor' => 'system'
            ]);
