{{-- Horizon footer: opaque earth-toned ground with sunset horizon line --}}
<footer class="mt-16 md:mt-24 relative bg-[#1a1f1a] w-full">
    {{-- Horizon line - last light between sky and earth --}}
    <div class="absolute inset-x-0 top-0 h-[2px] bg-gradient-to-r from-[rgba(255,170,120,0)] via-[rgba(255,190,140,.45)] to-[rgba(255,170,120,0)]" aria-hidden="true"></div>

    {{-- Subtle mountain ridge silhouette at horizon --}}
    <svg 
        class="w-full h-8 md:h-10 text-[#141814] absolute top-0 left-0"
        viewBox="0 0 1200 40" 
        fill="currentColor" 
        preserveAspectRatio="none"
        aria-hidden="true"
    >
        <path d="M0,40 L0,20 Q200,8 400,18 T800,15 Q1000,10 1200,25 L1200,40 Z" opacity="0.4"/>
        <path d="M0,40 L0,28 Q150,18 300,22 Q450,26 600,20 Q750,14 900,24 Q1050,32 1200,28 L1200,40 Z" opacity="0.6"/>
    </svg>

    {{-- Back to top button positioned at horizon apex --}}
    <a 
        href="#top"
        aria-label="{{ __('ui.back_to_top') ?? 'Back to top' }}"
        class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10
               w-11 h-11 rounded-full 
               bg-[#2a2f2a] border border-[rgba(255,190,140,.3)] backdrop-blur-md
               flex items-center justify-center
               transition-all duration-200
               hover:bg-[#343a34] hover:border-[rgba(255,190,140,.5)] hover:-translate-y-[calc(50%+2px)]
               focus-visible:outline-2 focus-visible:outline-[color:var(--star)] focus-visible:outline-offset-2
               group"
        @click.prevent="window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })"
    >
        <svg 
            class="w-5 h-5 text-[rgba(255,190,140,.7)] group-hover:text-[rgba(255,190,140,.9)] group-hover:scale-110 transition-all" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor" 
            stroke-width="2"
            aria-hidden="true"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </a>

    {{-- Footer content on the earth/ground --}}
    <div class="section">
        <div class="text-center space-y-3 pt-16 md:pt-20 pb-8">
            <p class="text-sm text-white/60 leading-relaxed">
                {{ __('ui.footer_note_primary') }}
            </p>
            <p class="text-xs text-white/40">
                &copy; {{ date('Y') }} Ursa Minor Games. All rights reserved.
            </p>
        </div>
    </div>
</footer>

