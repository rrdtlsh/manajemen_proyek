<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;  // <-- WAJIB: Memperbaiki error 'Undefined type'
use CodeIgniter\HTTP\ResponseInterface; // <-- WAJIB: Dibutuhkan oleh Interface

class AuthFilter implements FilterInterface
{
    /**
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Cek jika sudah login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // 2. Cek Role (PENTING!)
        $role = $session->get('role');
        $uri = service('uri'); // service('uri') tidak butuh $request

        // Jika BUKAN Pemilik mencoba akses halaman owner
        if ($role != 'Pemilik' && $uri->getSegment(1) == 'owner') {
            // Paksa redirect ke halaman karyawan
            return redirect()->to('/karyawan'); 
        }

        // Jika BUKAN Pemilik mencoba akses halaman manajemen
        if ($role != 'Pemilik' && $uri->getSegment(1) == 'manajemen') {
            return redirect()->to('/karyawan'); 
        }
        
        // Jika $role adalah 'owner', dia akan lolos semua 'if' di atas 
        // dan diizinkan mengakses halaman.
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa
    }
}