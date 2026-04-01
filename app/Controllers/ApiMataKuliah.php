<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MataKuliahModel;

class ApiMataKuliah extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $mkModel = new MataKuliahModel();
        return $this->respond($mkModel->findAll(), 200);
    }

    public function show($id = null)
    {
        $mkModel = new MataKuliahModel();
        $data = $mkModel->find($id);
        if ($data) {
            return $this->respond($data, 200);
        }
        return $this->failNotFound('Data mata kuliah tidak ditemukan.');
    }

    public function create()
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->respond(['success' => false, 'message' => 'Data kosong atau format salah'], 400);
        }

        $mkModel = new MataKuliahModel();

        // Validasi duplikasi kode_mk jika diperlukan (opsional, tergantung database)
        if ($mkModel->where('kode_mk', $data->kode_mk)->first()) {
            return $this->respond(['success' => false, 'message' => 'Kode Mata Kuliah sudah ada!'], 409);
        }

        $insertData = [
            'kode_mk'               => $data->kode_mk,
            'nama_mk'               => $data->nama_mk,
            'prodi'                 => $data->prodi ?? null,
            'prasyarat_sks_minimal' => $data->prasyarat_sks_minimal ?? 0,
            'jenis_mk'              => $data->jenis_mk ?? 'Wajib',
            'sks'                   => $data->sks,
            'semester'              => $data->semester
        ];

        try {
            $mkModel->insert($insertData);
            return $this->respondCreated(['success' => true, 'message' => 'Data Mata Kuliah berhasil disimpan!']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->respond(['success' => false, 'message' => 'Tidak ada data untuk diupdate'], 400);
        }

        $mkModel = new MataKuliahModel();
        if (!$mkModel->find($id)) {
            return $this->failNotFound('Data mata kuliah tidak ditemukan.');
        }

        $updateData = [
            'kode_mk'               => $data->kode_mk,
            'nama_mk'               => $data->nama_mk,
            'prodi'                 => $data->prodi ?? null,
            'prasyarat_sks_minimal' => $data->prasyarat_sks_minimal ?? 0,
            'jenis_mk'              => $data->jenis_mk ?? 'Wajib',
            'sks'                   => $data->sks,
            'semester'              => $data->semester
        ];

        try {
            $mkModel->update($id, $updateData);
            return $this->respond(['success' => true, 'message' => 'Data Mata Kuliah berhasil diupdate!']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id = null)
    {
        $mkModel = new MataKuliahModel();
        if ($mkModel->find($id)) {
            $mkModel->delete($id);
            return $this->respondDeleted(['success' => true, 'message' => "Data mata kuliah berhasil dihapus."]);
        }
        return $this->failNotFound("Data mata kuliah tidak ditemukan.");
    }
}