<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table            = 'nilai';
    protected $primaryKey       = 'id_nilai';
    protected $allowedFields    = ['nim', 'id_mk', 'id_kelas', 'nidn', 'nilai_angka', 'nilai_huruf'];

    // Menampilkan riwayat nilai yang sudah diinput oleh dosen ini
    public function getRiwayatNilai($nidn)
    {
        return $this->select('nilai.*, mahasiswa.nama_mahasiswa, mata_kuliah.nama_mk')
                    ->join('mahasiswa', 'mahasiswa.nim = nilai.nim')
                    ->join('mata_kuliah', 'mata_kuliah.id_mk = nilai.id_mk')
                    ->where('nilai.nidn', $nidn)
                    ->orderBy('nilai.id_nilai', 'DESC')
                    ->findAll();
    }
}