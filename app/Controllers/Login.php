<?php

namespace App\Controllers;

// WAJIB: Pastikan Anda 'use' Model dan BaseController
use App\Models\UserModel;
use App\Controllers\BaseController;

class Login extends BaseController
{
    /**
     * Method ini untuk MENAMPILKAN halaman login
     * (Dipanggil saat Anda membuka http://localhost:8080/login)
     */
    public function index()
    {
        $data = [
            'title' => 'Login'
        ];

        // Memuat file: app/Views/auth/login.php
        return view('auth/login', $data);
    }

    /**
     * Method ini untuk MEMPROSES data login
     * (Dipanggil oleh form action=".../login/auth")
     */
    public function auth()
    {
        $session = session();
        $model = new \App\Models\UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user) {

            if (password_verify($password, $user['password'])) {

                // 3. Buat Session
                $ses_data = [
                    'user_id'    => $user['id_user'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // 4. Arahkan (Redirect) BERDASARKAN ROLE

                // [PERBAIKAN LOGIKA]
                // Kita gunakan $user['role'] (dari database) untuk menentukan tujuan
                $role = strtolower($user['role']); // Ubah jadi huruf kecil untuk pencocokan

                if ($role == 'owner' || $role == 'pemilik') {
                    return redirect()->to('/owner'); // Ke dashboard Owner

                } elseif ($role == 'keuangan') {
                    // [PERBAIKAN UTAMA] Arahkan ke rute laporan keuangan baru
                    return redirect()->to('/karyawan/keuangan/laporan');
                } elseif ($role == 'inventaris') {
                    return redirect()->to('/karyawan/inventaris');
                } elseif ($role == 'penjualan') {
                    return redirect()->to('/karyawan/dashboard'); // Ke dashboard Karyawan Penjualan

                } else {
                    // Fallback jika rolenya 'staff' atau lainnya
                    return redirect()->to('/karyawan/dashboard');
                }
            } else {
                $session->setFlashdata('error', 'Password yang Anda masukkan salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    /**
     * Method untuk Logout
     */
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
