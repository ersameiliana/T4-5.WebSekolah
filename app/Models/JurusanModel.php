<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table            = 'jurusan';
    protected $primaryKey       = 'id_jurusan';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $allowedFields    = [
        'kode_jurusan', 
        'nama_jurusan', 
        'kepala_jurusan',   // Bisa diisi NIDN
        'akreditasi', 
        'deskripsi_jurusan'
    ];
}