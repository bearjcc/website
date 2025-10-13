# Dependencies Documentation

Overview of installed dependencies and their usage in the Ursa Minor Games project.

## Production Dependencies (composer.json)

### Core Framework

- **laravel/framework** (v12): Core Laravel framework
- **laravel/tinker** (v2.10): REPL for debugging and testing

### Frontend & UI

- **livewire/livewire** (v3.6): Full-stack reactive components
- **livewire/flux** (v2.5): UI component library (free tier)
  - Status: ✅ In use (buttons, cards)
  - Custom styling applied for night-sky theme
  
- **blade-ui-kit/blade-heroicons** (v2.6): Heroicons for Blade
  - Status: ✅ In use throughout (navigation, icons, motifs)

### SEO & Utilities

- **spatie/laravel-sitemap** (v7.3): XML sitemap generation
  - Status: ⚠️ Installed but not configured
  - Action: Configure in future or remove if not ready for SEO
  - Keep for now (will be needed for Phase 2+)

## Development Dependencies (composer.json - require-dev)

### Code Quality

- **laravel/pint** (v1.24): Code formatting (PSR-12)
  - Status: ✅ Configured and in use
  - Run before commits: `./vendor/bin/pint`

- **laravel/boost** (v1.3): Laravel MCP server integration
  - Status: ✅ In use with Cursor

### Testing

- **phpunit/phpunit** (v11.5): Testing framework
  - Status: ✅ 33 tests passing

### Development Tools

- **barryvdh/laravel-debugbar** (v3.16): Debug toolbar
  - Status: ✅ Local development only
  
- **barryvdh/laravel-ide-helper** (v3.6): IDE autocomplete
  - Status: ✅ Improves Cursor AI understanding
  
- **laravel/sail** (v1.41): Docker development environment
  - Status: ⚠️ Not actively used (using Herd instead)
  - Action: Keep for Docker consistency

- **laravel/pail** (v1.2): Log viewer
  - Status: ✅ Available for debugging

## NPM Dependencies (package.json)

### Build Tools

- **vite** (v7.0): Frontend build tool
  - Status: ✅ Active (`npm run dev`)
  
- **laravel-vite-plugin** (v2.0): Laravel integration for Vite
  - Status: ✅ Configured
  
- **tailwindcss** (v3.4): Utility-first CSS framework
  - Status: ✅ Full TALL stack implementation
  
- **postcss** (v8.4) + **autoprefixer** (v10.4): CSS processing
  - Status: ✅ Required for Tailwind

### Frontend Framework

- **alpinejs** (v3.15): Lightweight JS framework
  - Status: ✅ In use (carousel, dropdowns)
  
- **axios** (v1.11): HTTP client
  - Status: ✅ Loaded by Livewire/Laravel
  
- **embla-carousel** (v8.6): Carousel library
  - Status: ✅ IN USE (homepage game carousel)
  - Replaced complex custom 3D carousel

### Development Tools

- **concurrently** (v9.0): Run multiple npm scripts
  - Status: ✅ Used in `composer run dev` script

## Dependency Usage Status

### Fully Utilized ✅
- Laravel framework
- Livewire
- Flux UI (with custom styling)
- Blade Heroicons
- Tailwind CSS
- Alpine.js
- Embla Carousel (NOW IN USE)
- Laravel Pint
- PHPUnit

### Configured But Not Yet Used ⚠️
- Spatie Sitemap (keep for SEO in Phase 2+)
- Laravel Sail (using Herd, but keep for Docker compatibility)

### Potential for Removal 🤔
- None currently - all dependencies serve a purpose

## Maintenance

### Updating Dependencies

```powershell
# Check for updates
composer outdated
npm outdated

# Update (test thoroughly after)
composer update
npm update

# Run tests
php artisan test

# Format code
./vendor/bin/pint
```

### Before Adding New Dependencies

Ask:
1. Does this solve a specific problem?
2. Is there a simpler solution using existing tools?
3. What's the bundle size impact?
4. Is it actively maintained?
5. Does it align with minimal philosophy?

## Railway-Specific Considerations

### Production Build

The Dockerfile handles:
- `composer install --no-dev` (production dependencies only)
- `npm run build` (compile assets)
- No dev dependencies in production image

### Environment Variables

See `docs/RAILWAY_ENV.md` for required Railway configuration.

## Future Planned Dependencies

### Phase 2: Browser Games
- Possibly game-specific libraries (chess engine, etc.)
- Keep dependencies minimal

### Phase 3+
- Authentication packages (if needed beyond Laravel defaults)
- Real-time features (Laravel Reverb?)
- Image optimization tools

## Questions?

- **Should I remove a dependency?** Check usage first with grep/search
- **Should I add a dependency?** Consider if existing tools can solve it
- **Dependency conflicts?** Run `composer why` and `composer why-not`

