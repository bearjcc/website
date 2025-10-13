/**
 * Nav Logo Morph
 * 
 * Morphs the nav logo visibility based on hero lockup scroll position.
 * When hero lockup is visible, nav logo fades out to avoid duplication.
 * When hero scrolls out of view, nav logo fades in.
 * Respects prefers-reduced-motion.
 */

(function() {
    'use strict';

    // Check for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let observer = null;

    /**
     * Initialize the morph behavior
     */
    function init() {
        // Find the hero target and nav logo
        const heroTarget = document.querySelector('[data-um-hero-lockup]');
        const navLogo = document.querySelector('[data-um-morph="nav-logo"]');

        if (!heroTarget || !navLogo) {
            // If either element doesn't exist, nothing to morph
            return;
        }

        // If reduced motion, just toggle visibility without animation
        if (prefersReducedMotion) {
            setupStaticMorph(heroTarget, navLogo);
        } else {
            setupAnimatedMorph(heroTarget, navLogo);
        }
    }

    /**
     * Setup static morph (no animation, respect reduced motion)
     */
    function setupStaticMorph(heroTarget, navLogo) {
        observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.intersectionRatio >= 0.25) {
                    // Hero is visible - hide nav logo
                    navLogo.style.display = 'none';
                } else {
                    // Hero is not visible - show nav logo
                    navLogo.style.display = '';
                }
            });
        }, {
            threshold: [0, 0.25, 0.5, 0.75, 1.0],
            rootMargin: '-64px 0px 0px 0px' // Account for header height
        });

        observer.observe(heroTarget);
    }

    /**
     * Setup animated morph (smooth transitions)
     */
    function setupAnimatedMorph(heroTarget, navLogo) {
        // Set initial state
        navLogo.style.transition = 'opacity 180ms ease-in-out, transform 180ms ease-in-out';

        observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const ratio = entry.intersectionRatio;
                
                if (entry.isIntersecting && ratio >= 0.25) {
                    // Hero is visible - fade out and shrink nav logo
                    navLogo.style.opacity = '0';
                    navLogo.style.transform = 'scale(0.95)';
                    navLogo.style.pointerEvents = 'none';
                } else {
                    // Hero is not visible - fade in and restore nav logo
                    navLogo.style.opacity = '1';
                    navLogo.style.transform = 'scale(1)';
                    navLogo.style.pointerEvents = '';
                }
            });
        }, {
            threshold: [0, 0.25, 0.5, 0.75, 1.0],
            rootMargin: '-64px 0px 0px 0px' // Account for header height
        });

        observer.observe(heroTarget);
    }

    /**
     * Cleanup function
     */
    function destroy() {
        if (observer) {
            observer.disconnect();
            observer = null;
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose destroy for cleanup if needed
    window.navMorph = { destroy };
})();

