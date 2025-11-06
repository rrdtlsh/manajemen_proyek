<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel; // Pastikan ini adalah nama model Anda

class Login extends BaseController // Sesuaikan nama Class ini
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['session', 'url']); // Load helper session dan url
    }

    // Fungsi untuk MENAMPILKAN halaman login
    public function index()
    {
        // Jika user SUDAH login, lempar langsung ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboardowner'); // Ganti ini ke rute dashboard Anda
        }
        
        // Jika belum, tampilkan view login
        return view('auth/login'); 
    }

    // Fungsi untuk MEMPROSES upaya login
    // Ini adalah target dari rute '/login/auth' Anda
    public function auth()
    {
        // 1. Ambil data dari form
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // 2. Cari user di database
        $user = $this->userModel->getUserByUsername($username);

        // 3. Pengecekan
        // Apakah user-nya ada? DAN Apakah passwordnya cocok?
        if ($user && password_verify($password, $user['password'])) {
            
            // 4. JIKA BERHASIL: Buat Sesi
            session()->set([
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'role'      => $user['role'],
                'isLoggedIn' => true
            ]);

            // 5. Arahkan ke dashboard
            return redirect()->to('/dashboardowner'); // Ganti ini ke rute dashboard Anda

        } else {
            
            // 6. JIKA GAGAL:
            // Kembalikan ke halaman login dengan pesan error
            return redirect()->back()->with('error', 'Username atau password salah.');
        }
    }

    // Fungsi untuk logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}