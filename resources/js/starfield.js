/**
 * Starfield Animation
 * 
 * Generates a gentle, performant starfield that fills the entire document height
 * with subtle twinkling stars. Respects prefers-reduced-motion.
 */

(function() {
    'use strict';

    // Check for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Star configuration
    const STAR_DENSITY = 0.00015; // stars per pixel²
    const MIN_STAR_COUNT = 120;
    const MAX_STAR_COUNT = 350;
    const TWINKLE_SPEED_MIN = 0.1; // Hz
    const TWINKLE_SPEED_MAX = 0.3; // Hz
    const TWINKLE_AMPLITUDE = 0.15; // max alpha change

    let canvas, ctx, stars = [];
    let animationFrameId = null;
    let resizeTimeout = null;

    /**
     * Initialize the starfield
     */
    function init() {
        // Create canvas element
        canvas = document.createElement('canvas');
        canvas.id = 'um-starfield';
        canvas.setAttribute('aria-hidden', 'true');
        canvas.setAttribute('role', 'presentation');
        canvas.setAttribute('tabindex', '-1');
        canvas.style.cssText = 'position: fixed; inset: 0; pointer-events: none; z-index: 0;';

        // Insert as first child of body
        if (document.body.firstChild) {
            document.body.insertBefore(canvas, document.body.firstChild);
        } else {
            document.body.appendChild(canvas);
        }

        ctx = canvas.getContext('2d', { alpha: true });

        // Initial setup
        resizeCanvas();
        createStars();

        // Start animation loop if motion is allowed
        if (!prefersReducedMotion) {
            animate();
        } else {
            // Render once for static display
            renderStars();
        }

        // Handle window resize with debounce
        window.addEventListener('resize', handleResize);
    }

    /**
     * Resize canvas to match full document height
     */
    function resizeCanvas() {
        const width = window.innerWidth;
        const height = document.documentElement.scrollHeight;

        // Only resize if dimensions changed significantly
        if (Math.abs(canvas.width - width) > 16 || Math.abs(canvas.height - height) > 16) {
            canvas.width = width;
            canvas.height = height;
            return true;
        }
        return false;
    }

    /**
     * Handle window resize with debounce
     */
    function handleResize() {
        if (resizeTimeout) {
            clearTimeout(resizeTimeout);
        }

        resizeTimeout = setTimeout(() => {
            const didResize = resizeCanvas();
            if (didResize) {
                createStars();
                if (prefersReducedMotion) {
                    renderStars();
                }
            }
        }, 150);
    }

    /**
     * Generate stars based on canvas size
     */
    function createStars() {
        const area = canvas.width * canvas.height;
        const count = Math.min(
            MAX_STAR_COUNT,
            Math.max(MIN_STAR_COUNT, Math.floor(area * STAR_DENSITY))
        );

        stars = [];
        for (let i = 0; i < count; i++) {
            stars.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                radius: 0.4 + Math.random() * 0.8, // 0.4 to 1.2px
                baseAlpha: 0.2 + Math.random() * 0.4, // 0.2 to 0.6
                twinkleSpeed: TWINKLE_SPEED_MIN + Math.random() * (TWINKLE_SPEED_MAX - TWINKLE_SPEED_MIN),
                twinklePhase: Math.random() * Math.PI * 2
            });
        }
    }

    /**
     * Render all stars at current state
     */
    function renderStars(time = 0) {
        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw each star
        stars.forEach(star => {
            // Calculate alpha with subtle twinkle
            let alpha = star.baseAlpha;
            if (!prefersReducedMotion && time) {
                const twinkle = Math.sin(time * 0.001 * star.twinkleSpeed * Math.PI * 2 + star.twinklePhase);
                alpha = star.baseAlpha + (twinkle * TWINKLE_AMPLITUDE);
            }

            ctx.fillStyle = `rgba(255, 255, 255, ${alpha})`;
            ctx.beginPath();
            ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
            ctx.fill();
        });
    }

    /**
     * Animation loop
     */
    function animate(time = 0) {
        renderStars(time);
        animationFrameId = requestAnimationFrame(animate);
    }

    /**
     * Cleanup function
     */
    function destroy() {
        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }
        if (resizeTimeout) {
            clearTimeout(resizeTimeout);
        }
        window.removeEventListener('resize', handleResize);
        if (canvas && canvas.parentNode) {
            canvas.parentNode.removeChild(canvas);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose destroy for cleanup if needed
    window.starfield = { destroy };
})();

