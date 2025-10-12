# Ursa Minor Games - Project Structure

This document explains how the project is organized and where to find/add things.

## Directory Overview

```
website/
├── app/                    # Application core
├── bootstrap/              # Framework bootstrap
├── config/                 # Configuration files
├── database/               # Database migrations & seeders
├── docs/                   # Documentation
├── public/                 # Publicly accessible files
├── resources/              # Views, assets, lang files
├── routes/                 # Application routes
├── storage/                # App storage (logs, cache, uploads)
├── tests/                  # Automated tests
└── vendor/                 # Composer dependencies (gitignored)
```

## Key Directories

### `/app` - Application Logic

Contains all application code: models, controllers, Livewire components, services, etc.

**Structure:**
- `Games/` - Game engine classes
- `Http/Controllers/` - Request handlers
- `Livewire/` - Livewire components
- `Models/` - Eloquent models
- `Policies/` - Authorization policies
- `Providers/` - Service providers

### `/database` - Database Files

**Migrations:** Database schema definitions
**Seeders:** Sample data for development
**Factories:** Model factories for testing

### `/docs` - Documentation

All project documentation in markdown format:
- Brand guidelines
- Deployment guide
- Design foundations
- Feature extraction guide
- Project structure (this file)
- Roadmap
- TODO list

### `/public` - Public Assets

Static files served directly by the web server:
- Entry point (`index.php`)
- Compiled assets (`build/`)
- Static images (`bear.svg`, etc.)
- `robots.txt`, `favicon.ico`

### `/resources` - Application Resources

**Views:** Blade templates (layouts, components, pages)
**CSS:** Tailwind CSS styles
**JS:** Frontend JavaScript (Alpine.js)
**Lang:** Translation files

### `/routes` - Route Definitions

- `web.php` - Web routes
- `console.php` - Artisan commands

### `/tests` - Automated Tests

- `Feature/` - Integration/feature tests
- `Unit/` - Unit tests

## File Organization

### Naming Conventions

**Controllers:** Singular nouns (`GameController`, not `GamesController`)
**Models:** Singular (`Game`, `User`, `Post`)
**Views:** kebab-case (`game-card.blade.php`)
**Routes:** Use named routes (`->name('games.index')`)
**Database:** Tables plural, snake_case (`games`, `user_scores`)

### Component Structure

```
resources/views/
├── components/
│   ├── layouts/
│   │   ├── app.blade.php           # Main layout
│   │   └── private.blade.php       # Private/auth layout
│   ├── ui/
│   │   ├── card.blade.php          # Card component
│   │   ├── cta-row.blade.php       # CTA buttons
│   │   ├── logo-lockup.blade.php   # Logo component
│   │   └── section-header.blade.php # Section headers
│   ├── footer.blade.php
│   └── public-card.blade.php
├── livewire/
│   ├── auth/                       # Auth components
│   ├── games/                      # Game components
│   └── pages/                      # Page components
├── pages/
│   ├── about.blade.php
│   ├── contact.blade.php
│   ├── home.blade.php
│   └── games/                      # Game-specific pages
└── partials/
    ├── footer.blade.php
    └── nav.blade.php
```

## Adding New Features

### Checklist

1. Create feature branch: `git checkout -b feature/feature-name`
2. Create migration if needed: `php artisan make:migration`
3. Create model if needed: `php artisan make:model`
4. Create controller/Livewire component
5. Add routes in `routes/web.php`
6. Create views in `resources/views`
7. Write tests in `tests/Feature`
8. Update documentation
9. Create pull request

### Game Integration

See [FEATURE_EXTRACTION_GUIDE.md](FEATURE_EXTRACTION_GUIDE.md) for detailed instructions on integrating games from existing repositories.

## Configuration

### Environment Variables

Never use `env()` directly outside config files. Always use `config('app.name')`.

### Key Config Files

- `config/app.php` - Application settings
- `config/database.php` - Database connections
- `config/cache.php` - Cache configuration
- `config/session.php` - Session management

## Asset Management

### Vite

Frontend assets are bundled with Vite:
- Entry point: `resources/js/app.js`
- Styles: `resources/css/app.css`
- Build command: `npm run build`
- Dev server: `npm run dev`

### Images

Use `@images/` alias for imports:
```js
import logo from '@images/logo.svg'
```

## Testing

### Running Tests

```powershell
# All tests
php artisan test

# Specific file
php artisan test tests/Feature/HomePageTest.php

# With coverage
php artisan test --coverage
```

### Test Organization

- Feature tests: End-to-end functionality
- Unit tests: Isolated logic testing
- Target: 80%+ code coverage

## Deployment

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for complete deployment instructions.

**Quick reference:**
- Push to `main` triggers auto-deploy
- Railway handles build and deployment
- Environment variables configured in Railway dashboard

## Documentation

All documentation lives in `/docs` folder:
- README.md (index)
- BRAND_GUIDELINES.md
- DEPLOYMENT_GUIDE.md
- DESIGN_FOUNDATIONS.md
- FEATURE_EXTRACTION_GUIDE.md
- PROJECT_STRUCTURE.md (this file)
- ROADMAP.md
- TODO.md

Root level only has:
- README.md (project overview)
- CONTRIBUTING.md (development workflow)

---

**Built under the stars** | **© Ursa Minor Games**

