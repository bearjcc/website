/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Oswald', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        // Major Third (1.25) type scale from base 16px
        'body': ['1rem', { lineHeight: '1.5', letterSpacing: '0' }],        // 16px
        'h6': ['1.25rem', { lineHeight: '1.2', letterSpacing: '-0.01em' }],   // 20px
        'h5': ['1.563rem', { lineHeight: '1.2', letterSpacing: '-0.01em' }],  // 25px
        'h4': ['1.953rem', { lineHeight: '1.2', letterSpacing: '-0.015em' }], // 31.25px
        'h3': ['2.441rem', { lineHeight: '1.1', letterSpacing: '-0.015em' }], // 39px
        'h2': ['3.052rem', { lineHeight: '1.1', letterSpacing: '-0.02em' }],  // 48.8px
        'h1': ['3.815rem', { lineHeight: '1.1', letterSpacing: '-0.02em' }],  // 61px
      },
      spacing: {
        // 8-point spacing system
        '8': '0.5rem',    // 8px
        '16': '1rem',     // 16px
        '24': '1.5rem',   // 24px
        '32': '2rem',     // 32px
        '48': '3rem',     // 48px
        '64': '4rem',     // 64px
        '96': '6rem',     // 96px
        '128': '8rem',    // 128px
      },
    },
  },
  plugins: [],
}
