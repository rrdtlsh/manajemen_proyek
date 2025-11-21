<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - <?= esc($store_info['name']) ?></title>
    <meta name="description" content="Temukan koleksi karpet, bedcover, sprei, dan sejadah berkualitas tinggi di <?= esc($store_info['name']) ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>">
    <script src="<?= base_url('js/homepage.js') ?>" defer></script>

    <style>
        /* Internal Styling untuk Tampilan Lebih Cantik */
        :root {
            --primary-color: #2d8659;
            /* Warna hijau khas */
            --secondary-color: #f8f9fa;
            --text-dark: #333;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Poppins', sans-serif;

            /* --- PERUBAHAN DI SINI --- */
            /* Warna dasar hijau muda yang sangat lembut */
            background-color: #e8f5e9;

            /* Tambahkan gradasi halus supaya tidak polos (datar) */
            background-image: linear-gradient(to bottom, #298f12ff 0%, #0a2e0dff 100%);

            /* Pastikan background memenuhi halaman */
            min-height: 100vh;
            background-attachment: fixed;

            color: var(--text-dark);
        }

        /* Navbar Styling */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-dark) !important;
            transition: color 0.3s;
            margin-left: 15px;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 80vh;
            min-height: 500px;
            background: url('<?= base_url('images/toko-1.webp') ?>') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            /* Dark overlay */
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 20px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        /* Product Section */
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }

        /* Product Card */
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 10px;
            background: white;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .product-image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 100%;
            /* 1:1 Aspect Ratio */
            overflow: hidden;
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        /* Carousel Buttons Override */
        .carousel-btn {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 5;
        }

        .carousel-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* About Us */
        .about-section {
            background-color: #f0f4f1;
            border-radius: 20px;
            margin-top: 3rem;
        }

        .about-card {
            background: none;
            border: none;
            box-shadow: none;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .feature-item i {
            color: var(--primary-color);
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Footer */
        .footer {
            background-color: #222;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }

        .footer p {
            margin: 0;
            font-weight: 300;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="<?= base_url('images/logo-karpet.jpeg') ?>" alt="Logo" class="rounded-circle" style="height:45px; width:45px; object-fit: cover;">
                <span><?= esc($store_info['name']) ?></span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#karpet">Karpet</a></li>
                    <li class="nav-item"><a class="nav-link" href="#bedcover">Bedcover</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sprei">Sprei</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sejadah">Sejadah</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="https://wa.me/6282149586655" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-bold">
                            <i class="bi bi-whatsapp me-2"></i> Hubungi Kami
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="hero-title">Kenyamanan di Setiap Langkah</h1>
            <p class="hero-subtitle">Pusat Karpet, Bedcover, dan Sprei Terlengkap & Berkualitas di Banjarmasin</p>
            <a href="#karpet" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold text-uppercase" style="color: var(--primary-color);">
                Lihat Koleksi
            </a>
        </div>
    </header>

    <main class="main-content">
        <div class="container">

            <?php if (!empty($karpet)): ?>
                <section id="karpet" class="product-section py-5 text-center">
                    <h2 class="section-title">Koleksi Karpet</h2>
                    <div class="product-carousel-wrapper position-relative mt-4">
                        <button class="carousel-btn carousel-btn-prev position-absolute start-0 top-50 translate-middle-y" data-carousel="karpet">
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <div class="product-carousel overflow-hidden px-3" data-carousel="karpet">
                            <div class="product-carousel-track d-flex gap-3">
                                <?php foreach ($karpet as $item): ?>
                                    <div class="product-card" style="min-width: 280px; width: 280px;">
                                        <div class="product-image-wrapper">
                                            <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category']) ?>" class="product-image"
                                                onclick="showImageInModal(this.src, '<?= esc($item['category']) ?>')" style="cursor: pointer;">
                                            <div class="product-overlay d-flex align-items-end">
                                                <p class="text-white m-0 fw-bold"><?= esc($item['category']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <button class="carousel-btn carousel-btn-next position-absolute end-0 top-50 translate-middle-y" data-carousel="karpet">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($bedcover)): ?>
                <section id="bedcover" class="product-section py-5 text-center bg-light rounded-4">
                    <h2 class="section-title">Koleksi Bedcover</h2>
                    <div class="product-carousel-wrapper position-relative mt-4">
                        <button class="carousel-btn carousel-btn-prev position-absolute start-0 top-50 translate-middle-y" data-carousel="bedcover">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="product-carousel overflow-hidden px-3" data-carousel="bedcover">
                            <div class="product-carousel-track d-flex gap-3">
                                <?php foreach ($bedcover as $item): ?>
                                    <div class="product-card" style="min-width: 280px; width: 280px;">
                                        <div class="product-image-wrapper">
                                            <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category']) ?>" class="product-image"
                                                onclick="showImageInModal(this.src, '<?= esc($item['category']) ?>')" style="cursor: pointer;">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="carousel-btn carousel-btn-next position-absolute end-0 top-50 translate-middle-y" data-carousel="bedcover">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($sprei)): ?>
                <section id="sprei" class="product-section py-5 text-center">
                    <h2 class="section-title">Koleksi Sprei</h2>
                    <div class="product-carousel-wrapper position-relative mt-4">
                        <button class="carousel-btn carousel-btn-prev position-absolute start-0 top-50 translate-middle-y" data-carousel="sprei">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="product-carousel overflow-hidden px-3" data-carousel="sprei">
                            <div class="product-carousel-track d-flex gap-3">
                                <?php foreach ($sprei as $item): ?>
                                    <div class="product-card" style="min-width: 280px; width: 280px;">
                                        <div class="product-image-wrapper">
                                            <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category']) ?>" class="product-image"
                                                onclick="showImageInModal(this.src, '<?= esc($item['category']) ?>')" style="cursor: pointer;">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="carousel-btn carousel-btn-next position-absolute end-0 top-50 translate-middle-y" data-carousel="sprei">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($sejadah)): ?>
                <section id="sejadah" class="product-section py-5 text-center bg-light rounded-4">
                    <h2 class="section-title">Koleksi Sejadah</h2>
                    <div class="product-carousel-wrapper position-relative mt-4">
                        <button class="carousel-btn carousel-btn-prev position-absolute start-0 top-50 translate-middle-y" data-carousel="sejadah">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="product-carousel overflow-hidden px-3" data-carousel="sejadah">
                            <div class="product-carousel-track d-flex gap-3">
                                <?php foreach ($sejadah as $item): ?>
                                    <div class="product-card" style="min-width: 280px; width: 280px;">
                                        <div class="product-image-wrapper">
                                            <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category']) ?>" class="product-image"
                                                onclick="showImageInModal(this.src, '<?= esc($item['category']) ?>')" style="cursor: pointer;">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="carousel-btn carousel-btn-next position-absolute end-0 top-50 translate-middle-y" data-carousel="sejadah">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </section>
            <?php endif; ?>

            <section id="about" class="about-section py-5 px-3 px-md-5 mb-5">

                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8 text-center">
                        <h6 class="text-success fw-bold text-uppercase ls-1 mb-2">Tentang Kami</h6>
                        <h2 class="fw-bold display-6 text-dark">TOKO KARPET H.JALI</h2>
                        <div class="mx-auto mt-3" style="width: 60px; height: 3px; background-color: #2d8659;"></div>
                    </div>
                </div>

                <div class="row align-items-stretch g-5">
                    <div class="col-lg-6">
                        <div class="about-image position-relative h-100">
                            <img src="<?= base_url('images/toko-1.webp') ?>"
                                alt="Toko <?= esc($store_info['name']) ?>"
                                class="img-fluid rounded-4 shadow-lg w-100 h-100"
                                style="object-fit: cover;">

                            <div class="position-absolute bottom-0 end-0 bg-white p-3 rounded-4 shadow m-3 border border-success border-opacity-25">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                                        <i class="bi bi-trophy-fill text-success fs-3"></i>
                                    </div>
                                    <div>
                                        <span class="h3 fw-bold text-success mb-0 d-block">5+</span>
                                        <span class="small text-muted fw-medium">Tahun Pengalaman</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex flex-column justify-content-center">
                        <p class="text-muted mb-4 lead" style="line-height: 1.8;">
                            Selamat datang di <span class="fw-bold text-dark"><?= esc($store_info['name']) ?></span>! Kami berdedikasi untuk menyediakan produk perlengkapan rumah tangga berkualitas tinggi.
                            Kami memahami bahwa kenyamanan rumah bermula dari detail kecil seperti karpet yang lembut, sprei yang nyaman, dan suasana yang hangat.
                        </p>

                        <div class="row mb-4">
                            <div class="col-6 mb-3">
                                <div class="feature-item d-flex align-items-center p-2 rounded bg-white shadow-sm">
                                    <i class="bi bi-patch-check-fill fs-4 text-success me-2"></i>
                                    <span class="fw-medium">Kualitas Premium</span>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="feature-item d-flex align-items-center p-2 rounded bg-white shadow-sm">
                                    <i class="bi bi-tag-fill fs-4 text-success me-2"></i>
                                    <span class="fw-medium">Harga Terbaik</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-item d-flex align-items-center p-2 rounded bg-white shadow-sm">
                                    <i class="bi bi-shop fs-4 text-success me-2"></i>
                                    <span class="fw-medium">Stok Lengkap</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-item d-flex align-items-center p-2 rounded bg-white shadow-sm">
                                    <i class="bi bi-geo-alt-fill fs-4 text-success me-2"></i>
                                    <span class="fw-medium">Lokasi Strategis</span>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 bg-white shadow-sm border-start border-success border-4 mt-auto">
                            <div class="card-body">
                                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-clock me-2 text-success"></i>Jam Operasional</h6>
                                <p class="mb-0 text-secondary">Senin - Minggu: 08.00 - 17.00 WITA</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5 g-4">
                    <div class="col-lg-8">
                        <div class="maps-container rounded-4 overflow-hidden shadow-sm" style="height: 350px;">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.285692865271!2d114.59190547489083!3d-3.324713197175023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de423003e29bd57%3A0x9b4caea68c6a5ad2!2sTOKO%20H.JALI!5e0!3m2!1sen!2sid!4v1699373547788!5m2!1sen!2sid"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm bg-success text-white rounded-4 overflow-hidden position-relative">
                            <div class="card-body p-4 d-flex flex-column justify-content-center position-relative z-2">
                                <h4 class="fw-bold mb-4 border-bottom border-white border-opacity-25 pb-3">Hubungi Kami</h4>
                                <p class="mb-4 fs-5"><i class="bi bi-geo-alt me-2 opacity-75"></i>Jl. Brigjen H. Hasan Basri No.12, Sungai Lulut, Banjarmasin</p>
                                <div class="d-grid gap-3">
                                    <a href="https://wa.me/6282149586655?text=Halo,%20saya%20tertarik%20dengan%20produk%20di%20Toko%20H.Jali" target="_blank" class="btn btn-light btn-lg fw-bold shadow-sm text-success">
                                        <i class="bi bi-whatsapp me-2"></i> Chat WhatsApp
                                    </a>
                                    <a href="https://www.google.com/maps?ll=-3.324713,114.59441&z=17&t=m&hl=en&gl=ID&mapclient=embed&cid=11207744710097877714" target="_blank" class="btn btn-outline-light btn-lg fw-bold">
                                        <i class="bi bi-map me-2"></i> Petunjuk Arah
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-md-12">
                    <h5 class="fw-bold text-success mb-2"><?= esc($store_info['name']) ?></h5>
                    <p class="text-muted small mb-3">Kenyamanan Rumah Anda, Prioritas Kami.</p>
                    <p class="mb-0 small text-secondary">&copy; <?= date('Y') ?> All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 position-relative text-center">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img src="" class="img-fluid rounded shadow-lg" id="modalImage" alt="Zoom Image">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showImageInModal(src, title) {
            document.getElementById('modalImage').src = src;
            var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
            myModal.show();
        }
    </script>
</body>

</html>