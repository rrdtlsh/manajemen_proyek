// Carousel dengan tombol panah
document.addEventListener('DOMContentLoaded', function() {
    const carouselWrappers = document.querySelectorAll('.product-carousel-wrapper');
    
    carouselWrappers.forEach(function(wrapper) {
        const carousel = wrapper.querySelector('.product-carousel');
        const track = wrapper.querySelector('.product-carousel-track');
        const prevBtn = wrapper.querySelector('.carousel-btn-prev');
        const nextBtn = wrapper.querySelector('.carousel-btn-next');
        
        if (!carousel || !track || !prevBtn || !nextBtn) return;
        
        const cardWidth = 280; // Sesuai dengan width product-card
        const gap = 20; // Sesuai dengan gap di CSS
        const scrollAmount = cardWidth + gap;
        
        let currentScroll = 0;
        let maxScroll = track.scrollWidth - carousel.offsetWidth;
        
        // Update button states
        function updateButtons() {
            prevBtn.disabled = currentScroll <= 0;
            nextBtn.disabled = currentScroll >= maxScroll;
        }
        
        // Initial button state
        updateButtons();
        
        // Next button
        nextBtn.addEventListener('click', function() {
            if (currentScroll < maxScroll) {
                currentScroll = Math.min(currentScroll + scrollAmount, maxScroll);
                track.style.transform = `translateX(-${currentScroll}px)`;
                updateButtons();
            }
        });
        
        // Prev button
        prevBtn.addEventListener('click', function() {
            if (currentScroll > 0) {
                currentScroll = Math.max(currentScroll - scrollAmount, 0);
                track.style.transform = `translateX(-${currentScroll}px)`;
                updateButtons();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const newMaxScroll = track.scrollWidth - carousel.offsetWidth;
            if (currentScroll > newMaxScroll) {
                currentScroll = newMaxScroll;
                track.style.transform = `translateX(-${currentScroll}px)`;
            }
            maxScroll = newMaxScroll;
            updateButtons();
        });
        
        // Touch/swipe support untuk mobile
        let touchStartX = 0;
        let touchScrollLeft = 0;
        let isDragging = false;
        
        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].pageX;
            touchScrollLeft = currentScroll;
            isDragging = true;
        });
        
        carousel.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.touches[0].pageX;
            const walk = (touchStartX - x) * 2;
            const newScroll = Math.max(0, Math.min(maxScroll, touchScrollLeft + walk));
            currentScroll = newScroll;
            track.style.transform = `translateX(-${currentScroll}px)`;
            updateButtons();
        });
        
        carousel.addEventListener('touchend', function() {
            isDragging = false;
            // Snap to nearest card
            const snappedScroll = Math.round(currentScroll / scrollAmount) * scrollAmount;
            currentScroll = Math.max(0, Math.min(maxScroll, snappedScroll));
            track.style.transform = `translateX(-${currentScroll}px)`;
            updateButtons();
        });
    });
});
