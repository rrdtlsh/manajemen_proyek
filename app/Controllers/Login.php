<?php

namespace App\Controllers;

// WAJIB: Pastikan Anda 'use' Model dan BaseController
use App\Models\UserModel;
use App\Controllers\BaseController;

class Login extends BaseController
{
    /**
     * Method ini untuk MENAMPILKAN halaman login
     */
    public function index()
    {
        $data = [
            'title' => 'Login'
        ];
        return view('auth/login', $data);
    }

    /**
     * Method ini untuk MEMPROSES data login
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
                $role = strtolower($user['role']);

                if ($role == 'owner' || $role == 'pemilik') {
                    return redirect()->to('/owner'); // Ke dashboard Owner

                } elseif ($role == 'keuangan') {
                    // [PERBAIKAN] Arahkan ke rute karyawan/keuangan/laporan
                    return redirect()->to('karyawan/keuangan/laporan');
                } elseif ($role == 'inventaris') {
                    // [PERBAIKAN] Arahkan ke rute karyawan/inventaris
                    return redirect()->to('karyawan/inventaris');
                } elseif ($role == 'penjualan') {
                    // Ini sudah benar, mengarah ke redirector Karyawan
                    return redirect()->to('/karyawan/dashboard');
                } else {
                    // Fallback
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
