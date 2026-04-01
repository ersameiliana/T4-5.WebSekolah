<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika belum login, tendang ke halaman login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak! Silakan login terlebih dahulu.');
        }

        // Pengecekan Role (Opsional di tingkat filter, tapi kita lakukan validasi role spesifik di controller)
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu logic setelah request
    }
}