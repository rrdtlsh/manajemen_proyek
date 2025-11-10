<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah pengguna sudah login
        if (!session()->get('isLoggedIn')) {
            // Jika belum, lempar ke halaman login
            return redirect()->to('/login');
        }

        // 2. Ambil role dari session (yang sudah di-set saat login)
        $userRole = session()->get('role');

        // 3. Cek jika rute ini butuh role khusus (ada $arguments)
        if (!empty($arguments)) {
            $isAllowed = false;
            foreach ($arguments as $allowedRole) {
                // Cek role (case-insensitive, misal 'Keuangan' sama dengan 'keuangan')
                if (strcasecmp($userRole, $allowedRole) == 0) {
                    $isAllowed = true;
                    break;
                }
            }

            // 4. Jika role pengguna tidak ada di daftar $arguments
            if (!$isAllowed) {
                // Tentukan dashboard default untuk role ini
                $role = strtolower($userRole);
                $redirectURL = '/'; // Halaman default

                if ($role == 'owner' || $role == 'pemilik') {
                    $redirectURL = '/owner';
                } elseif ($role == 'penjualan') {
                    $redirectURL = '/karyawan/dashboard';
                } elseif ($role == 'keuangan') {
                    // [PENTING] Arahkan ke rute laporan yang benar
                    $redirectURL = '/karyawan/keuangan/laporan';
                } elseif ($role == 'inventaris') {
                    $redirectURL = '/karyawan/inventaris';
                }

                // Kembalikan pengguna ke dashboard mereka dengan pesan error
                return redirect()->to(base_url($redirectURL))->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }
        // Jika lolos semua cek, lanjutkan ke controller
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah request
    }
}
