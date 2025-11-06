<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        // Set locale berdasarkan session atau default
        $session = session();
        $locale = $session->get('locale') ?: 'id';
        $this->request->setLocale($locale);
        
        // Pass locale ke view untuk menghindari error
        $data = [
            'currentLocale' => $locale
        ];
        
        return view('auth/login', $data);
    }

    public function switchLanguage($lang)
    {
        // Validasi bahasa yang diizinkan
        $allowedLangs = ['id', 'en'];
        if (!in_array($lang, $allowedLangs)) {
            $lang = 'id';
        }

        // Simpan di session
        $session = session();
        $session->set('locale', $lang);
        
        // Redirect kembali ke halaman login
        return redirect()->to(base_url('login'));
    }

    public function auth()
    {
        // Handle login authentication
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // TODO: Implementasi logika autentikasi di sini
        // Contoh sederhana (ganti dengan logika autentikasi yang sebenarnya):
        // - Validasi username dan password
        // - Cek ke database
        // - Set session jika berhasil
        // - Redirect ke dashboard jika berhasil
        // - Tampilkan error jika gagal
        
        // Untuk sementara, redirect ke homepage sebagai contoh
        // Setelah implementasi autentikasi, ganti dengan redirect ke dashboard
        return redirect()->to(base_url('homepage'));
    }
}