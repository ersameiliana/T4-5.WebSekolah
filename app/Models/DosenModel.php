<?php
namespace App\Models;
use CodeIgniter\Model;

class DosenModel extends Model {
    protected $table = 'dosen';
    protected $primaryKey = 'nidn';
    protected $allowedFields = ['nama_dosen', 'gelar_depan', 'gelar_belakang', 'fakultas', 'prodi', 'status_dosen', 'jabatan_struktural', 'tanggal_lahir', 'no_telp', 'password'];
}