// Carousel dengan tombol panah
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                // Close navbar dropdown if open on mobile
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
            }
        });
    });

    // Setup image modal functionality
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');

    // Add click event to all product images
    document.querySelectorAll('.product-image').forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function(e) {
            e.preventDefault();
            modalImage.src = this.src;
            modalTitle.textContent = this.alt;
            imageModal.show();
        });
    });

    const carouselWrappers = document.querySelectorAll('.product-carousel-wrapper');
    
    carouselWrappers.forEach(function(wrapper) {
        const carousel = wrapper.querySelector('.product-carousel');
        const track = wrapper.querySelector('.product-carousel-track');
        const prevBtn = wrapper.querySelector('.carousel-btn-prev');
        const nextBtn = wrapper.querySelector('.carousel-btn-next');
        
        if (!carousel || !track || !prevBtn || !nextBtn) return;
        
        const cardWidth = 300; // Sesuai dengan width product-card yang baru
        const gap = 20; // Sesuai dengan gap di CSS
        const scrollAmount = cardWidth + gap;
        
        let currentIndex = 0;
        const totalItems = track.children.length;
        
        // Clone items for infinite loop
        const items = Array.from(track.children);
        items.forEach(item => {
            const clone = item.cloneNode(true);
            // Pastikan event click untuk modal juga ter-copy
            const img = clone.querySelector('.product-image');
            if (img) {
                img.addEventListener('click', function(e) {
                    e.preventDefault();
                    modalImage.src = this.src;
                    modalTitle.textContent = this.alt;
                    imageModal.show();
                });
            }
            track.appendChild(clone);
        });
        
        function updatePosition(animate = true) {
            const translateX = -currentIndex * scrollAmount;
            track.style.transition = animate ? 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)' : 'none';
            track.style.transform = `translateX(${translateX}px)`;
        }
        
        // Initial position
        updatePosition(false);
        
        // Next button
        nextBtn.addEventListener('click', function() {
            currentIndex++;
            updatePosition(true);
            
            // If we've scrolled to the cloned items
            if (currentIndex >= totalItems) {
                setTimeout(() => {
                    currentIndex = 0;
                    updatePosition(false);
                }, 300); // Match with transition duration
            }
        });
        
        // Prev button
        prevBtn.addEventListener('click', function() {
            currentIndex--;
            
            if (currentIndex < 0) {
                currentIndex = totalItems - 1;
                updatePosition(false);
                requestAnimationFrame(() => {
                    currentIndex = totalItems - 1;
                    updatePosition(true);
                });
            } else {
                updatePosition(true);
            }
        });
        
        // Touch/swipe support untuk mobile
        let touchStartX = 0;
        let touchStartY = 0;
        let initialIndex = 0;
        let isDragging = false;
        
        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].pageX;
            touchStartY = e.touches[0].pageY;
            initialIndex = currentIndex;
            isDragging = true;
            track.style.transition = 'none';
        });
        
        carousel.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            
            const x = e.touches[0].pageX;
            const y = e.touches[0].pageY;
            const xDiff = touchStartX - x;
            const yDiff = touchStartY - y;
            
            if (Math.abs(yDiff) > Math.abs(xDiff)) {
                isDragging = false;
                return;
            }
            
            e.preventDefault();
            const walk = xDiff;
            track.style.transform = `translateX(${-(initialIndex * scrollAmount + walk)}px)`;
        });
        
        carousel.addEventListener('touchend', function() {
            if (!isDragging) return;
            isDragging = false;
            
            const moveBy = Math.round((touchStartX - event.changedTouches[0].pageX) / scrollAmount);
            currentIndex = initialIndex + moveBy;
            
            if (currentIndex < 0) {
                currentIndex = totalItems - 1;
                updatePosition(false);
                requestAnimationFrame(() => {
                    updatePosition(true);
                });
            } else if (currentIndex >= totalItems) {
                currentIndex = 0;
                updatePosition(true);
            } else {
                updatePosition(true);
            }
        });
    });
});
