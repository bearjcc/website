@props([
    'slides' => [],
    'showDots' => true,
    'showArrows' => true,
])

<div 
    x-data="window.initEmblaCarousel()" 
    x-init="init()"
    class="um-carousel relative"
    {{ $attributes }}
>
    {{-- Carousel viewport --}}
    <div class="overflow-hidden" x-ref="viewport">
        <div class="flex gap-4 md:gap-6">
            {{ $slot }}
        </div>
    </div>

    {{-- Navigation arrows (glass effect) --}}
    @if($showArrows)
        <button 
            type="button"
            @click="scrollPrev()"
            class="um-carousel-arrow um-carousel-arrow-prev"
            aria-label="Previous slide"
        >
            <x-heroicon-o-chevron-left class="w-5 h-5" aria-hidden="true" />
        </button>

        <button 
            type="button"
            @click="scrollNext()"
            class="um-carousel-arrow um-carousel-arrow-next"
            aria-label="Next slide"
        >
            <x-heroicon-o-chevron-right class="w-5 h-5" aria-hidden="true" />
        </button>
    @endif

    {{-- Constellation pagination dots --}}
    @if($showDots)
        <div class="um-carousel-dots mt-6 flex items-center justify-center gap-3">
            <template x-for="(snap, index) in scrollSnaps" :key="index">
                <div class="flex items-center gap-3">
                    {{-- Star dot --}}
                    <button
                        type="button"
                        @click="scrollTo(index)"
                        class="um-carousel-dot"
                        :class="{ 'um-carousel-dot-active': selectedIndex === index }"
                        :aria-label="'Go to slide ' + (index + 1)"
                        :aria-current="selectedIndex === index ? 'true' : 'false'"
                    >
                        <span class="sr-only" x-text="'Slide ' + (index + 1)"></span>
                    </button>
                    
                    {{-- Constellation line (except after last dot) --}}
                    <span 
                        x-show="index < scrollSnaps.length - 1" 
                        class="um-constellation-line"
                        aria-hidden="true"
                    ></span>
                </div>
            </template>
        </div>
    @endif
</div>

