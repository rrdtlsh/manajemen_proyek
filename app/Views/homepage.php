<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - <?= esc($store_info['name']) ?></title>
    <link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>">
    <script src="<?= base_url('js/homepage.js') ?>" defer></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo-wrapper">
                <img src="<?= base_url('images/logo-karpet.jpeg') ?>" alt="Logo <?= esc($store_info['name']) ?>" class="logo">
                <h1 class="store-name"><?= esc($store_info['name']) ?></h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Section Karpet -->
            <?php if (!empty($karpet)): ?>
            <section class="product-section">
                <h2 class="section-title">Karpet</h2>
                <div class="product-carousel-wrapper">
                    <button class="carousel-btn carousel-btn-prev" data-carousel="karpet" aria-label="Previous">
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
                                    <div class="product-overlay">
                                        <span class="product-category"><?= esc($item['category']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-next" data-carousel="karpet" aria-label="Next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </section>
            <?php endif; ?>

            <!-- Store Info Card -->
            <section class="store-info-section">
                <div class="store-info-card">
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
                       class="whatsapp-button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.98 2.898 1.857 1.867 2.892 4.35 2.892 6.99 0 5.45-4.436 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" fill="currentColor"/>
                        </svg>
                        Hubungi Kami via WhatsApp
                    </a>
                </div>
            </section>

            <!-- Section Bedcover -->
            <?php if (!empty($bedcover)): ?>
            <section class="product-section">
                <h2 class="section-title">Bedcover</h2>
                <div class="product-carousel-wrapper">
                    <button class="carousel-btn carousel-btn-prev" data-carousel="bedcover" aria-label="Previous">
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
                                    <div class="product-overlay">
                                        <span class="product-category"><?= esc($item['category']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-next" data-carousel="bedcover" aria-label="Next">
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
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= esc($store_info['name']) ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

