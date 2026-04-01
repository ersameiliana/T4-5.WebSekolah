<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($arguments && !in_array($role, $arguments)) {
            // Jika role tidak sesuai, lemparkan ke dashboard masing-masing
            return redirect()->to("/{$role}/dashboard");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}