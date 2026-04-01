<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukturOrganisasiModel extends Model
{
    protected $table            = 'struktur_organisasi';
    protected $primaryKey       = 'id_struktur';
    protected $allowedFields    = ['kategori', 'jabatan', 'nama_pejabat'];
}