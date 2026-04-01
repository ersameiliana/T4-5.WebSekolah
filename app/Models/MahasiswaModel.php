<?php
namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model {
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    
    // Matikan auto-increment bawaan CI4 karena NIM pakai sistem Trigger manual
    protected $useAutoIncrement = false; 

    protected $allowedFields = [
        'nim',              // <--- WAJIB: Agar nilai 0 dari form bisa masuk dan memicu Trigger SQL
        'nama_mahasiswa', 
        'fakultas', 
        'prodi', 
        'semester', 
        'jalur_masuk', 
        'tanggal_lahir', 
        'no_telp', 
        'password', 
        'id_guest_wali',    // <--- EXTRA: Ditambahkan sesuai struktur tabel Anda
        'status_studi'
    ];
}