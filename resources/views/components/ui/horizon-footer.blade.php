{{-- Horizon footer: opaque earth-toned ground with sunset horizon line --}}
<footer class="mt-16 md:mt-24 relative bg-earth w-full isolate">
    {{-- Horizon line - last light between sky and earth --}}
    <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-transparent via-sunset/45 to-transparent" aria-hidden="true"></div>

    {{-- Subtle mountain ridge silhouette at horizon (darker earth tone) --}}
    <div class="absolute inset-x-0 top-0 h-6 bg-gradient-to-b from-earth-dark to-transparent opacity-50" aria-hidden="true"></div>

    {{-- Back to top star rocket button --}}
    <button 
        id="um-back-to-top"
        aria-label="{{ __('ui.back_to_top') ?? 'Back to top' }}"
        class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10
               w-11 h-11 rounded-full 
               bg-earth-button border border-sunset-border/30
               flex items-center justify-center
               transition-all duration-200
               hover:bg-earth-button-hover hover:border-sunset-border/50 hover:scale-110
               focus-visible:outline-2 focus-visible:outline-star focus-visible:outline-offset-2
               group cursor-pointer"
    >
        {{-- Star rocket icon --}}
        <svg 
            class="w-5 h-5 text-star transition-all group-hover:scale-110" 
            fill="currentColor" 
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path d="M12 2L9 9l-7 3 7 3 3 7 3-7 7-3-7-3-3-7z"/>
        </svg>
    </button>

    {{-- Footer content on the earth/ground - compact like header --}}
    <div class="section">
        <div class="flex items-center justify-center gap-6 py-6 text-center">
            <p class="text-sm text-ink/60 leading-relaxed">
                {{ __('ui.footer_note_primary') }}
            </p>
            <span class="text-ink/20">•</span>
            <p class="text-sm text-ink/40">
                &copy; {{ date('Y') }} Ursa Minor Games. All rights reserved.
            </p>
        </div>
    </div>
</footer>

{{-- Star rocket launch animation script --}}
<script>
(function() {
    'use strict';
    
    const button = document.getElementById('um-back-to-top');
    if (!button) return;
    
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (prefersReducedMotion) {
            // Instant scroll for reduced motion
            window.scrollTo({ top: 0, behavior: 'auto' });
            return;
        }
        
        // Create star trail particles
        const particles = [];
        const buttonRect = button.getBoundingClientRect();
        const startX = buttonRect.left + buttonRect.width / 2;
        const startY = buttonRect.top + buttonRect.height / 2;
        
        for (let i = 0; i < 8; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                left: ${startX}px;
                top: ${startY}px;
                width: 4px;
                height: 4px;
                background: var(--star);
                border-radius: 50%;
                pointer-events: none;
                z-index: 9999;
                opacity: 0.8;
            `;
            document.body.appendChild(particle);
            particles.push(particle);
            
            // Animate particle downward (rocket exhaust)
            const angle = (Math.PI / 4) + (Math.random() - 0.5) * (Math.PI / 6);
            const distance = 20 + Math.random() * 30;
            const duration = 300 + Math.random() * 200;
            
            particle.animate([
                { transform: 'translate(0, 0) scale(1)', opacity: 0.8 },
                { 
                    transform: `translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px) scale(0)`, 
                    opacity: 0 
                }
            ], {
                duration: duration,
                easing: 'ease-out'
            }).onfinish = () => particle.remove();
        }
        
        // Accelerating scroll animation
        const duration = 1000;
        const start = window.pageYOffset;
        const startTime = performance.now();
        
        function animateScroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Ease-in-out cubic for smooth acceleration/deceleration
            const eased = progress < 0.5
                ? 4 * progress * progress * progress
                : 1 - Math.pow(-2 * progress + 2, 3) / 2;
            
            window.scrollTo(0, start * (1 - eased));
            
            if (progress < 1) {
                requestAnimationFrame(animateScroll);
            }
        }
        
        requestAnimationFrame(animateScroll);
    });
})();
</script>

