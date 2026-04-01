<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukturPimpinanModel extends Model
{
    protected $table            = 'struktur_pimpinan';
    protected $primaryKey       = 'id_pimpinan';
    protected $allowedFields    = ['kategori', 'nama_unit', 'jabatan', 'nidn_pejabat', 'nama_pejabat_teks'];

    // Method untuk mengambil pimpinan beserta relasi nama dosen (jika NIDN terisi)
    public function getPimpinan()
    {
        return $this->select('struktur_pimpinan.*, dosen.nama_dosen, dosen.gelar_depan, dosen.gelar_belakang')
                    ->join('dosen', 'dosen.nidn = struktur_pimpinan.nidn_pejabat', 'left')
                    ->findAll();
    }
}