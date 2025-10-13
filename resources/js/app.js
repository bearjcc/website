import './bootstrap';
import './starfield';
import './nav-morph';
import './game-storage';
import Alpine from 'alpinejs';
import { initEmblaCarousel } from './embla-carousel';

// Expose Embla initializer to Alpine
window.initEmblaCarousel = initEmblaCarousel;

window.Alpine = Alpine;
Alpine.start();
