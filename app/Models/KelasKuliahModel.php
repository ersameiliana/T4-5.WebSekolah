<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasKuliahModel extends Model
{
    protected $table            = 'kelas_kuliah';
    protected $primaryKey       = 'id_kelas';
    protected $allowedFields    = ['kode_mk', 'nama_kelas', 'nidn', 'hari', 'jam_mulai', 'jam_selesai', 'ruang'];

    // Mengambil kelas beserta ID Mata Kuliah (karena tabel nilai butuh id_mk)
    public function getKelasDosen($nidn)
    {
        return $this->select('kelas_kuliah.*, mata_kuliah.id_mk, mata_kuliah.nama_mk, mata_kuliah.sks')
                    ->join('mata_kuliah', 'mata_kuliah.kode_mk = kelas_kuliah.kode_mk')
                    ->where('kelas_kuliah.nidn', $nidn)
                    ->findAll();
    }
}