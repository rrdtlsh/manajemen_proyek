<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function homepage(): string
    {
        // Ambil gambar karpet dan bedcover
        $karpetImages = $this->getProductImages('karpet');
        $bedcoverImages = $this->getProductImages('bedcover');

        $data = [
            'karpet' => $karpetImages,
            'bedcover' => $bedcoverImages,
            'store_info' => [
                'name' => 'SADANG THJ',
                'location' => 'Jl. Contoh No. 123, Kota Anda', // Ganti dengan alamat sebenarnya
                'whatsapp' => '6281234567890', // Ganti dengan nomor WhatsApp sebenarnya
            ]
        ];

        return view('homepage', $data);
    }

    private function getProductImages($type): array
    {
        $basePath = FCPATH . 'images' . DIRECTORY_SEPARATOR . 'gambar ' . $type . DIRECTORY_SEPARATOR;
        $images = [];

        if (!is_dir($basePath)) {
            return $images;
        }

        $categories = array_filter(glob($basePath . '*'), 'is_dir');

        foreach ($categories as $categoryPath) {
            $categoryName = basename($categoryPath);

            // Gunakan pendekatan yang lebih kompatibel untuk mencari file gambar
            $allowedExtensions = ['jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG'];
            $files = [];

            foreach ($allowedExtensions as $ext) {
                $pattern = $categoryPath . DIRECTORY_SEPARATOR . '*.' . $ext;
                $found = glob($pattern);
                if ($found) {
                    $files = array_merge($files, $found);
                }
            }

            // Ambil 2-3 gambar pertama dari setiap kategori sebagai contoh
            $categoryImages = array_slice($files, 0, 3);

            foreach ($categoryImages as $file) {
                if (is_file($file)) {
                    $images[] = [
                        'category' => $categoryName,
                        'path' => 'images/gambar ' . $type . '/' . $categoryName . '/' . basename($file),
                        'name' => basename($file)
                    ];
                }
            }
        }

        return $images;
    }
}
