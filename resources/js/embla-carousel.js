import EmblaCarousel from 'embla-carousel';

/**
 * Initialize Embla Carousel with constellation-themed pagination
 * Integrates with Alpine.js for reactive state management
 */
export function initEmblaCarousel() {
    return {
        embla: null,
        selectedIndex: 0,
        scrollSnaps: [],
        
        init() {
            const viewport = this.$refs.viewport;
            
            // Initialize Embla with options
            this.embla = EmblaCarousel(viewport, {
                loop: true,
                align: 'center',
                skipSnaps: false,
                dragFree: false,
            });
            
            // Set up scroll snap points
            this.scrollSnaps = this.embla.scrollSnapList();
            
            // Update selected index on scroll
            this.embla.on('select', () => {
                this.selectedIndex = this.embla.selectedScrollSnap();
            });
            
            // Initialize selected index
            this.selectedIndex = this.embla.selectedScrollSnap();
        },
        
        scrollPrev() {
            if (this.embla) this.embla.scrollPrev();
        },
        
        scrollNext() {
            if (this.embla) this.embla.scrollNext();
        },
        
        scrollTo(index) {
            if (this.embla) this.embla.scrollTo(index);
        },
        
        destroy() {
            if (this.embla) this.embla.destroy();
        }
    };
}

