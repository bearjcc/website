import './bootstrap';
import './starfield';
import './nav-morph';
import './game-storage';
import { initEmblaCarousel } from './embla-carousel';

// Expose Embla initializer to Alpine (Alpine is loaded by Livewire)
window.initEmblaCarousel = initEmblaCarousel;

// Enhanced mobile touch handling
document.addEventListener('DOMContentLoaded', function() {
    // Prevent zoom on double tap for game elements
    const gameElements = document.querySelectorAll('.game-board, .sudoku-board, .connect4-board, .minesweeper-board, .snake-board, .checkers-board, .chess-board, .game-board-2048');
    
    gameElements.forEach(element => {
        element.addEventListener('touchstart', function(e) {
            if (e.touches.length > 1) {
                e.preventDefault();
            }
        }, { passive: false });
        
        element.addEventListener('touchend', function(e) {
            e.preventDefault();
        }, { passive: false });
    });

    // Enhanced keyboard navigation for games
    document.addEventListener('keydown', function(e) {
        // Prevent default behavior for game controls
        if (e.target.closest('.game-board, .sudoku-board, .connect4-board, .minesweeper-board, .snake-board, .checkers-board, .chess-board, .game-board-2048')) {
            if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'w', 'a', 's', 'd'].includes(e.key)) {
                e.preventDefault();
            }
        }
    });

    // Add loading states for better UX
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({ fail, succeed }) => {
            // Add loading state
            const loadingElements = document.querySelectorAll('[wire\\:loading]');
            loadingElements.forEach(el => el.classList.add('loading-pulse'));
            
            succeed(() => {
                // Remove loading state
                loadingElements.forEach(el => el.classList.remove('loading-pulse'));
            });
            
            fail(() => {
                // Remove loading state on error
                loadingElements.forEach(el => el.classList.remove('loading-pulse'));
            });
        });
    });

    // Enhanced mobile viewport handling
    function setViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    
    setViewportHeight();
    
    // Throttled resize handler for better performance
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(setViewportHeight, 100);
    });
    
    window.addEventListener('orientationchange', function() {
        setTimeout(setViewportHeight, 100);
    });

    // Enhanced keyboard navigation for games
    document.addEventListener('keydown', function(e) {
        // Skip if user is typing in an input
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.contentEditable === 'true') {
            return;
        }

        // Game-specific keyboard shortcuts
        const gameContainer = e.target.closest('.game-board, .sudoku-board, .connect4-board, .minesweeper-board, .snake-board, .checkers-board, .chess-board, .game-board-2048');
        
        if (gameContainer) {
            // Prevent default browser behavior for game keys
            if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'w', 'a', 's', 'd', ' '].includes(e.key)) {
                e.preventDefault();
            }
        }
    });

    // Enhanced touch handling for mobile games
    let touchStartTime = 0;
    document.addEventListener('touchstart', function(e) {
        touchStartTime = Date.now();
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        const touchDuration = Date.now() - touchStartTime;
        
        // Prevent accidental double-tap zoom on game elements
        if (touchDuration < 300 && e.target.closest('.game-board, .sudoku-board, .connect4-board, .minesweeper-board, .snake-board, .checkers-board, .chess-board, .game-board-2048')) {
            e.preventDefault();
        }
    }, { passive: false });
});
