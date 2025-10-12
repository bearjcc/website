{{-- Simple footer with poetic tagline --}}
<footer class="mt-12 md:mt-16 py-6 md:py-8 border-t border-[color:var(--border)]">
    <div class="section text-center">
        <p class="text-sm text-[color:var(--ink-muted)]">
            {{ __('ui.footer_note') }}
        </p>
        <p class="text-xs text-[color:var(--ink-muted)] mt-2">
            &copy; {{ date('Y') }} Ursa Minor Games
        </p>
    </div>
</footer>

