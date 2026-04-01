<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\NilaiModel;

class ApiNilai extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $nilaiModel = new NilaiModel();
        return $this->respond($nilaiModel->findAll(), 200);
    }

    public function show($id = null)
    {
        $nilaiModel = new NilaiModel();
        $data = $nilaiModel->find($id);
        if ($data) {
            return $this->respond($data, 200);
        }
        return $this->failNotFound('Data nilai dengan ID ' . $id . ' tidak ditemukan.');
    }

    public function create()
    {
        $data = $this->request->getJSON();
        if (!$data) {
            return $this->respond(['success' => false, 'message' => 'Data kosong atau format salah'], 400);
        }

        $nilaiModel = new NilaiModel();

        $insertData = [
            'nim'         => $data->nim,
            'id_mk'       => $data->id_mk,
            'id_kelas'    => $data->id_kelas ?? null,
            'nidn'        => $data->nidn,
            'nilai_angka' => $data->nilai_angka ?? null,
            'nilai_huruf' => $data->nilai_huruf ?? null
        ];

        try {
            $nilaiModel->insert($insertData);
            return $this->respondCreated(['success' => true, 'message' => 'Data Nilai berhasil disimpan!']);
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

        $nilaiModel = new NilaiModel();
        if (!$nilaiModel->find($id)) {
            return $this->failNotFound('Data nilai tidak ditemukan.');
        }

        $updateData = [
            'nim'         => $data->nim,
            'id_mk'       => $data->id_mk,
            'id_kelas'    => $data->id_kelas ?? null,
            'nidn'        => $data->nidn,
            'nilai_angka' => $data->nilai_angka ?? null,
            'nilai_huruf' => $data->nilai_huruf ?? null
        ];

        try {
            $nilaiModel->update($id, $updateData);
            return $this->respond(['success' => true, 'message' => 'Data Nilai berhasil diupdate!']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id = null)
    {
        $nilaiModel = new NilaiModel();
        if ($nilaiModel->find($id)) {
            $nilaiModel->delete($id);
            return $this->respondDeleted(['success' => true, 'message' => "Data nilai dengan ID $id berhasil dihapus."]);
        }
        return $this->failNotFound("Data nilai tidak ditemukan.");
    }
}