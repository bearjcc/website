{{-- Horizon footer with elegant gradient line --}}
<footer class="mt-12 md:mt-16 py-8 md:py-10">
    <div class="section">
        {{-- Horizon line --}}
        <div class="h-[1px] bg-gradient-to-r from-white/0 via-white/20 to-white/0 mb-6" aria-hidden="true"></div>
        
        {{-- Footer content --}}
        <div class="text-center space-y-2">
            <p class="text-sm text-white/70">
                {{ __('ui.footer_note_primary') }}
            </p>
            <p class="text-xs text-white/50">
                &copy; {{ date('Y') }} Ursa Minor Games
            </p>
        </div>
    </div>
</footer>

