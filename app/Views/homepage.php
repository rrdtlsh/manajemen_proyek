<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - <?= esc($store_info['name']) ?></title>
    <meta name="description" content="Temukan koleksi karpet, bedcover, sprei, dan sejadah berkualitas tinggi di <?= esc($store_info['name']) ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>">
    <script src="<?= base_url('js/homepage.js') ?>" defer></script>
</head>
<body>
    <!-- Background: ganti URL di bawah dengan foto toko 1 & 2 -->
    <div class="page-bg">
        <div class="layer layer-1" style="background-image: url('<?= base_url('images/toko-1.webp') ?>'); animation: none;"></div>
    </div>
    <!-- Header -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                    <img src="<?= base_url('images/logo-karpet.jpeg') ?>" alt="Logo <?= esc($store_info['name']) ?>" class="logo" style="height:36px; width:auto;">
                    <span class="store-name mb-0"><?= esc($store_info['name']) ?></span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#karpet">Karpet</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bedcover">Bedcover</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sprei">Sprei</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sejadah">Sejadah</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Section Karpet -->
            <?php if (!empty($karpet)): ?>
            <section id="karpet" class="product-section py-4">
                <h2 class="section-title">Karpet</h2>
                <div class="product-carousel-wrapper">
                    <button class="carousel-btn carousel-btn-prev btn btn-outline-secondary" data-carousel="karpet" aria-label="Previous">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" fill="currentColor"/>
                        </svg>
                    </button>
                    <div class="product-carousel" data-carousel="karpet">
                        <div class="product-carousel-track">
                            <?php foreach ($karpet as $item): ?>
                            <div class="product-card">
                                <div class="product-image-wrapper">
                                    <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category']) ?>" class="product-image">
                                    <div class="product-overlay"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-next btn btn-outline-secondary" data-carousel="karpet" aria-label="Next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </section>
            <?php endif; ?>

            <!-- Section Sprei -->
            <?php if (!empty($sprei)): ?>
            <section id="sprei" class="product-section py-4">
                <h2 class="section-title">Sprei</h2>
                <div class="product-carousel-wrapper">
                    <button class="carousel-btn carousel-btn-prev btn btn-outline-secondary" data-carousel="sprei" aria-label="Previous">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" fill="currentColor"/>
                        </svg>
                    </button>
                    <div class="product-carousel" data-carousel="sprei">
                        <div class="product-carousel-track">
                            <?php foreach ($sprei as $item): ?>
                            <div class="product-card">
                                <div class="product-image-wrapper">
                                    <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category'] ?? 'Sprei') ?>" class="product-image">
                                    <div class="product-overlay"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-next btn btn-outline-secondary" data-carousel="sprei" aria-label="Next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </section>
            <?php endif; ?>

            <!-- Store Info Card -->
            <section class="store-info-section py-4">
                <div class="store-info-card p-4">
                    <div class="store-info-icon">
                        <i class="bi bi-shop-window" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="store-info-title">TOKO H.JALI</h3>
                    <div class="store-info-details">
                        <p class="store-info-address mb-2">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            Jl. Brigjen H. Hasan Basri No.12, Sungai Lulut, Kec. Banjarmasin Tim., Kota Banjarmasin, Kalimantan Selatan 70236
                        </p>
                        <div class="store-info-badges mb-3">
                            <span class="store-badge">
                                <i class="bi bi-star-fill"></i>
                                4.4 / 5.0
                            </span>
                            <span class="store-badge">
                                <i class="bi bi-clock-fill"></i>
                                Buka: 08.00 - 17.00
                            </span>
                        </div>
                        <div class="maps-container mb-3">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.285692865271!2d114.59190547489083!3d-3.324713197175023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de423003e29bd57%3A0x9b4caea68c6a5ad2!2sTOKO%20H.JALI!5e0!3m2!1sen!2sid!4v1699373547788!5m2!1sen!2sid" 
                                width="100%" 
                                height="250" 
                                style="border:0; border-radius: 12px;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="store-actions">
                            <a href="https://wa.me/<?= esc($store_info['whatsapp']) ?>?text=Halo,%20saya%20tertarik%20dengan%20produk%20karpet%20dan%20bedcover%20di%20toko%20Anda" 
                               target="_blank" 
                               class="whatsapp-button btn btn-success">
                                <i class="bi bi-whatsapp me-2"></i>
                                Hubungi via WhatsApp
                            </a>
                            <a href="https://www.google.com/maps?ll=-3.324713,114.59441&z=17&t=m&hl=en&gl=ID&mapclient=embed&cid=11207744710097877714" 
                               target="_blank" 
                               class="directions-button btn">
                                <i class="bi bi-map me-2"></i>
                                Petunjuk Arah
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section Bedcover -->
            <?php if (!empty($bedcover)): ?>
            <section id="bedcover" class="product-section py-4">
                <h2 class="section-title">Bedcover</h2>
                <div class="product-carousel-wrapper">
                    <button class="carousel-btn carousel-btn-prev btn btn-outline-secondary" data-carousel="bedcover" aria-label="Previous">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" fill="currentColor"/>
                        </svg>
                    </button>
                    <div class="product-carousel" data-carousel="bedcover">
                        <div class="product-carousel-track">
                            <?php foreach ($bedcover as $item): ?>
                            <div class="product-card">
                                <div class="product-image-wrapper">
                                    <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category']) ?>" class="product-image">
                                    <div class="product-overlay"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-next btn btn-outline-secondary" data-carousel="bedcover" aria-label="Next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </section>
            <?php endif; ?>

            <!-- Section Sejadah -->
            <?php if (!empty($sejadah)): ?>
            <section id="sejadah" class="product-section py-4">
                <h2 class="section-title">Sejadah</h2>
                <div class="product-carousel-wrapper">
                    <button class="carousel-btn carousel-btn-prev btn btn-outline-secondary" data-carousel="sejadah" aria-label="Previous">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" fill="currentColor"/>
                        </svg>
                    </button>
                    <div class="product-carousel" data-carousel="sejadah">
                        <div class="product-carousel-track">
                            <?php foreach ($sejadah as $item): ?>
                            <div class="product-card">
                                <div class="product-image-wrapper">
                                    <img src="<?= base_url($item['path']) ?>" alt="<?= esc($item['category'] ?? 'Sejadah') ?>" class="product-image">
                                    <div class="product-overlay"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-next btn btn-outline-secondary" data-carousel="sejadah" aria-label="Next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </section>
            <?php endif; ?>

            <!-- About Us Section -->
            <section id="about" class="about-section py-5">
                <div class="about-card">
                    <h2 class="section-title">Tentang Kami</h2>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="about-image">
                                <img src="<?= base_url('images/toko-1.webp') ?>" alt="Toko <?= esc($store_info['name']) ?>" class="img-fluid rounded shadow">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="about-content">
                                <h3 class="about-subtitle"><?= esc($store_info['name']) ?></h3>
                                <p class="about-text">
                                    Selamat datang di <?= esc($store_info['name']) ?>! Kami adalah toko yang berkomitmen untuk menyediakan produk berkualitas tinggi untuk kebutuhan rumah Anda. Dengan pengalaman bertahun-tahun dalam industri ini, kami memahami pentingnya kenyamanan dan estetika dalam setiap produk yang kami tawarkan.
                                </p>
                                <p class="about-text">
                                    Kami menyediakan berbagai pilihan karpet, bedcover, sprei, dan sejadah dengan desain modern dan klasik. Setiap produk dipilih dengan teliti untuk memastikan kualitas terbaik bagi pelanggan kami.
                                </p>
                                <div class="about-features">
                                    <div class="feature-item">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Produk Berkualitas</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="bi bi-star-fill"></i>
                                        <span>Pelayanan Terbaik</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="bi bi-truck"></i>
                                        <span>Pengiriman Cepat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= esc($store_info['name']) ?>. All rights reserved.</p>
        </div>
    </footer>
    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="" class="img-fluid" id="modalImage" alt="Product Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

