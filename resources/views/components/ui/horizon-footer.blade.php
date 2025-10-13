{{-- Horizon footer with calm, clear design --}}
<footer class="relative w-full isolate um-horizon-footer">
    {{-- Sunset line: warm 1-2px glow at horizon --}}
    <div class="um-horizon-line" aria-hidden="true"></div>

    {{-- Earth silhouette: darker desaturated band --}}
    <div class="um-horizon-silhouette" aria-hidden="true"></div>

    {{-- Back to top button: sits on sky side above horizon --}}
    <a 
        href="#top" 
        class="um-top-btn" 
        aria-label="{{ __('ui.back_to_top') }}"
    >
        <x-heroicon-o-star class="w-4 h-4" />
    </a>

    {{-- Footer content --}}
    <div class="section mt-6 text-center text-sm text-ink/70">
        <p class="mb-3 text-ink/60">{{ __('ui.footer_note_primary') }}</p>
        <p class="text-ink/50">&copy; {{ now()->year }} Ursa Minor Games. All rights reserved.</p>
    </div>
</footer>

{{-- Back to top scroll behavior --}}
<script>
(function() {
    'use strict';
    
    const button = document.querySelector('.um-top-btn');
    if (!button) return;
    
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (prefersReducedMotion) {
            window.scrollTo({ top: 0, behavior: 'auto' });
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
})();
</script>

