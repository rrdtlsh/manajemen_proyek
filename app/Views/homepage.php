<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - <?= esc($store_info['name']) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        <nav class="navbar navbar-expand-lg bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                    <img src="<?= base_url('images/logo-karpet.jpeg') ?>" alt="Logo <?= esc($store_info['name']) ?>" class="logo" style="height:48px; width:auto;">
                    <span class="store-name mb-0 h4"><?= esc($store_info['name']) ?></span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#karpet">Karpet</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bedcover">Bedcover</a></li>
                        <li class="nav-item"><a class="btn btn-outline-success ms-lg-3" href="<?= base_url('login') ?>">Login</a></li>
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
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                        </svg>
                    </div>
                    <h3 class="store-info-title">Kunjungi Toko Kami</h3>
                    <p class="store-info-location">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                        </svg>
                        <?= esc($store_info['location']) ?>
                    </p>
                    <a href="https://wa.me/<?= esc($store_info['whatsapp']) ?>?text=Halo,%20saya%20tertarik%20dengan%20produk%20karpet%20dan%20bedcover%20di%20toko%20Anda" 
                       target="_blank" 
                       class="whatsapp-button btn btn-success btn-lg mt-3">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.98 2.898 1.857 1.867 2.892 4.35 2.892 6.99 0 5.45-4.436 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" fill="currentColor"/>
                        </svg>
                        Hubungi Kami via WhatsApp
                    </a>
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
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= esc($store_info['name']) ?>. All rights reserved.</p>
        </div>
    </footer>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

