<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DosenModel;

class ApiDosen extends ResourceController
{
    protected $format = 'json';

    /**
     * Tampilkan daftar semua dosen
     */
    public function index()
    {
        $dosenModel = new DosenModel();
        // Mengambil semua data dosen (termasuk kolom gelar dan jabatan baru)
        return $this->respond($dosenModel->findAll(), 200);
    }

    /**
     * Tampilkan detail dosen berdasarkan NIDN
     */
    public function show($id = null)
    {
        $dosenModel = new DosenModel();
        $data = $dosenModel->find($id);
        if ($data) {
            return $this->respond($data, 200);
        }
        return $this->failNotFound('Data dosen dengan NIDN ' . $id . ' tidak ditemukan.');
    }

    /**
     * Simpan data dosen baru
     */
    public function create()
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->respond(['success' => false, 'message' => 'Data kosong atau format salah'], 400);
        }

        $dosenModel = new DosenModel();

        // Validasi duplikasi NIDN
        if ($dosenModel->find($data->nidn)) {
            return $this->respond(['success' => false, 'message' => 'NIDN sudah terdaftar!'], 409);
        }

        $insertData = [
            'nidn'               => $data->nidn,
            'nama_dosen'         => $data->nama_dosen,
            'gelar_depan'        => $data->gelar_depan ?? null, // Kolom Baru
            'gelar_belakang'     => $data->gelar_belakang ?? null, // Kolom Baru
            'fakultas'           => $data->fakultas,
            'prodi'              => $data->prodi,
            'status_dosen'       => $data->status_dosen,
            'jabatan_struktural' => $data->jabatan_struktural ?? 'Dosen Pengampu', // Kolom Baru
            'tanggal_lahir'      => $data->tanggal_lahir,
            'no_telp'            => $data->no_telp ?? null,
            'password'           => $data->password ?? '' // Trigger trg_dsn_insert akan otomatis mengisi jika kosong
        ];

        try {
            $dosenModel->insert($insertData);
            return $this->respondCreated(['success' => true, 'message' => 'Data Dosen berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update data dosen berdasarkan NIDN
     */
    public function update($id = null)
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->respond(['success' => false, 'message' => 'Tidak ada data untuk diupdate'], 400);
        }

        $dosenModel = new DosenModel();
        if (!$dosenModel->find($id)) {
            return $this->failNotFound('Data dosen tidak ditemukan.');
        }

        $updateData = [
            'nama_dosen'         => $data->nama_dosen,
            'gelar_depan'        => $data->gelar_depan ?? null, // Kolom Baru
            'gelar_belakang'     => $data->gelar_belakang ?? null, // Kolom Baru
            'fakultas'           => $data->fakultas,
            'prodi'              => $data->prodi,
            'status_dosen'       => $data->status_dosen,
            'jabatan_struktural' => $data->jabatan_struktural ?? 'Dosen Pengampu', // Kolom Baru
            'tanggal_lahir'      => $data->tanggal_lahir,
            'no_telp'            => $data->no_telp,
        ];

        // Jika password diisi di form, lakukan hash. Jika tidak, biarkan password lama.
        if (!empty($data->password)) {
            $updateData['password'] = $data->password; // Trigger database akan memvalidasi formatnya
        }

        try {
            $dosenModel->update($id, $updateData);
            return $this->respond(['success' => true, 'message' => 'Data Dosen berhasil diupdate!']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus data dosen
     */
    public function delete($id = null)
    {
        $dosenModel = new DosenModel();
        if ($dosenModel->find($id)) {
            $dosenModel->delete($id);
            return $this->respondDeleted(['success' => true, 'message' => "Data dosen dengan NIDN $id berhasil dihapus."]);
        }
        return $this->failNotFound("Data dosen tidak ditemukan.");
    }
}