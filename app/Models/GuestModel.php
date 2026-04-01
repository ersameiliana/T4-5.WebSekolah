<?php
namespace App\Models;
use CodeIgniter\Model;

class GuestModel extends Model {
    protected $table = 'guest';
    protected $primaryKey = 'id_guest';
    protected $allowedFields = ['jenis_akun', 'nama_lengkap', 'username', 'password', 'nim_mahasiswa', 'last_login'];
}