<?php

namespace App\Controllers;

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

                $ses_data = [
                    'user_id'    => $user['id_user'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                $role = strtolower($user['role']);

                if ($role == 'owner' || $role == 'pemilik' || $role == 'superadmin') {
                    return redirect()->to('/owner');
                } else {
                
                    return redirect()->to('karyawan/dashboard');
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

    /**
     * Method untuk ganti bahasa (jika Anda menggunakannya)
     */
    public function switchLanguage($language = 'id')
    {
        $session = session();
        $session->set('lang', $language);
        return redirect()->back();
    }
}
