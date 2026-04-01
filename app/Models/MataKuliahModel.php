<?php

namespace App\Models;

use CodeIgniter\Model;

class MataKuliahModel extends Model
{
    protected $table            = 'mata_kuliah';
    protected $primaryKey       = 'id_mk';
    
    protected $useAutoIncrement = true; 
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'kode_mk', 
        'nama_mk', 
        'prodi', 
        'prasyarat_sks_minimal', 
        'jenis_mk', 
        'sks', 
        'semester'
    ];

    protected $useTimestamps = false;
}