<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\GuestModel;

class ApiGuest extends ResourceController {
    protected $format = 'json';

    public function index()
    {
        $guestModel = new GuestModel();
        return $this->respond($guestModel->findAll(), 200);
    }

    public function create() {
        $data = $this->request->getJSON();
        if (!$data) return $this->respond(['success' => false, 'message' => 'Data kosong'], 400);

        $guestModel = new GuestModel();

        // 1. Cek apakah Username sudah dipakai (Karena di DB username itu UNIQUE)
        if ($guestModel->where('username', $data->username)->first()) {
            return $this->respond(['success' => false, 'message' => 'Username sudah digunakan, silakan pilih yang lain!'], 409);
        }

        // 2. Logika untuk nim_mahasiswa: jika "Tamu umum", pastikan nilainya NULL
        $nimMahasiswa = null;
        if ($data->jenis_akun === 'Orang tua/Wali Mahasiswa' && !empty($data->nim_mahasiswa)) {
            $nimMahasiswa = $data->nim_mahasiswa;
        }

        $insertData = [
            'jenis_akun'    => $data->jenis_akun,
            'nama_lengkap'  => $data->nama_lengkap,
            'username'      => $data->username,
            'password'      => $data->password, // Harus diisi manual dari form
            'nim_mahasiswa' => $nimMahasiswa
        ];

        try {
            // 3. Simpan ke database
            $guestModel->insert($insertData);
            return $this->respond(['success' => true, 'message' => 'Akun Guest berhasil dibuat!']);
            
        } catch (\Exception $e) {
            // JIKA PASSWORD LEMAH, TRIGGER MYSQL AKAN MENGIRIM ERROR KE SINI
            // Pesannya: "Password Guest minimal 6 karakter kombinasi huruf & angka."
            return $this->respond([
                'success' => false, 
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}