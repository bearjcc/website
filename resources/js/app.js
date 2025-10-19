import './bootstrap';
import './starfield';
import './nav-morph';
import './game-storage';
import { initEmblaCarousel } from './embla-carousel';

// Expose Embla initializer to Alpine (Alpine is loaded by Livewire)
window.initEmblaCarousel = initEmblaCarousel;
