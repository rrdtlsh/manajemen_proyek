<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek pengguna sudah login
        if (!session()->get('isLoggedIn')) {
            // klo belum, lempar ke halaman login
            return redirect()->to('/login');
        }

        // Ambil role dari session (yang sudah di-set saat login)
        $userRole = session()->get('role');
        
        if (strtolower($userRole) === 'superadmin') {
            return; // Langsung lolos ke controller
        }
        // cek jika rute ini butuh role khusus 
        if (!empty($arguments)) {
            $isAllowed = false;
            foreach ($arguments as $allowedRole) {
                // cek role (case-insensitive, misal 'Keuangan' sama dengan 'keuangan')
                if (strcasecmp($userRole, $allowedRole) == 0) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                // Tentukan dashboard default untuk role ini
                $role = strtolower($userRole);
                $redirectURL = '/'; // Halaman default

                if ($role == 'owner' || $role == 'pemilik') {
                    $redirectURL = '/owner';
                } elseif ($role == 'penjualan') {
                    $redirectURL = '/karyawan/dashboard';
                } elseif ($role == 'keuangan') {
                    $redirectURL = '/karyawan/keuangan/laporan';
                } elseif ($role == 'inventaris') {
                    $redirectURL = '/karyawan/inventaris';
                }

                return redirect()->to(base_url($redirectURL))->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}
