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

                // HAPUS 'dd($user['role']);' DARI SINI JIKA TADI ANDA MENAMBAHKANNYA

                // 3. Buat Session
                $ses_data = [
                    'user_id'    => $user['id_user'],
                    'username'   => $user['username'],
                    'role'       => $user['role'], // Ini akan berisi "Pemilik"
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // 4. Arahkan (Redirect) berdasarkan role
                // === INI PERBAIKANNYA ===
                if ($user['role'] == 'Pemilik') { 
                    return redirect()->to('/owner'); // Ke dashboard Owner
                } else {
                    return redirect()->to('/karyawan'); // Ke dashboard Karyawan
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