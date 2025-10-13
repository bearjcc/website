# Ursa Minor Games

A Laravel-based website for **Ursa Minor Games**, featuring browser-based games, F1 predictions, and more.

## About

Ursa Minor is a gaming brand focused on:
- **Browser Games**: Free-to-play classic games (sudoku, chess, etc.)
- **F1 Predictions**: Community-driven F1 race predictions and leaderboards
- **Board Games**: Digital versions of custom board game designs
- **Video Game Development**: Future ambitious video game projects

This repository contains the main homepage and will serve as the hub for all Ursa Minor gaming experiences.

## Current Features

- **Night Sky Design System**: Calm, minimal aesthetic with starfield background
- **Embla Carousel**: Visual-first game cards with constellation pagination
- **Responsive Design**: Laptop/tablet first, gracefully scales to desktop
- **Accessibility**: WCAG AA compliant, keyboard navigation, screen reader support
- **Horizon Footer**: Minimal footer with back-to-top navigation
- **Production Ready**: Deployed on Railway with Docker multi-stage builds

## Tech Stack

- **Framework**: Laravel 12.x
- **PHP**: 8.4
- **Frontend**: TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
- **UI Components**: Flux UI (free tier) + custom components
- **Icons**: Heroicons via Blade Heroicons
- **Build Tool**: Vite
- **Carousel**: Embla Carousel
- **Database**: SQLite (local), PostgreSQL (production)
- **Hosting**: Railway with Docker

## Development

### Prerequisites

- PHP 8.3 or higher
- Composer
- Git

### Local Setup with Laravel Herd

This project uses Laravel Herd for local development:

```powershell
# Clone the repository
git clone https://github.com/bearjcc/website.git
cd website

# Install dependencies
composer install
npm install

# Set up environment
Copy-Item -Path ".env.example" -Destination ".env"
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed initial data
php artisan db:seed --class=ProductionSeeder

# Start Vite dev server (in background)
npm run dev
```

Visit http://tavernsandtreasures.test/ (Laravel Herd automatically serves the site).

### Project Structure

```
website/
├── app/
│   ├── Games/          # Game engine logic (Connect4, Sudoku, etc.)
│   ├── Livewire/       # Livewire page & UI components
│   ├── Models/         # Eloquent models
│   └── Policies/       # Authorization policies
├── resources/
│   ├── css/
│   │   └── app.css    # Tailwind + custom styles
│   ├── js/
│   │   ├── app.js     # Main JS entry
│   │   ├── starfield.js    # Starfield animation
│   │   ├── nav-morph.js    # Nav logo morphing
│   │   └── embla-carousel.js  # Carousel helper
│   └── views/
│       ├── components/ # Blade components (UI, layouts)
│       └── livewire/   # Livewire component views
├── public/
│   ├── bear.svg       # Ursa Minor logo
│   └── build/         # Vite compiled assets
├── database/
│   ├── migrations/    # Database schema
│   └── seeders/       # Data seeders
├── tests/             # Test suite (33 tests passing)
│   ├── Feature/       # Feature tests
│   └── Unit/          # Unit tests
├── docs/              # Project documentation
├── .cursor/rules/     # Cursor AI rules
├── Dockerfile         # Multi-stage Docker build
└── deploy/            # Railway deployment configs
```

## Testing

### Running Tests

Run all tests:
```powershell
php artisan test
```

Run specific test file:
```powershell
php artisan test --filter=HomePageTest
```

Run tests with coverage:
```powershell
php artisan test --coverage
```

### Test Coverage

The project follows **humility protocol** - no changes should be merged without verification through tests.

**Homepage Tests** (`tests/Feature/HomePageTest.php`):
- ✅ Core sections render correctly
- ✅ No banned future-facing terms (café, storefront, etc.)
- ✅ Game cards limited to three
- ✅ Hero section with primary CTA
- ✅ Footer and navigation present

### Future Testing Goals

**Planned enhancements:**
- Browser testing with Laravel Dusk or Cypress for visual regression detection
- Static analysis to detect inline hex colors in views
- Performance testing for page load times
- Accessibility testing (WCAG AA compliance)

**Target:** 80%+ code coverage for all new features

## Deployment

### Deploying to Railway with Docker

This project uses a production-ready multi-stage Docker build for Railway deployments.

**Prerequisites:**
- Railway project linked to this GitHub repository
- Railway configured to use Dockerfile (not nixpacks)

**Required Railway Environment Variables:**
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generated-on-first-deploy>
APP_URL=https://your-app.up.railway.app
LOG_CHANNEL=stderr
LOG_LEVEL=info

# Database (if using Railway Postgres)
DATABASE_URL=${{Postgres.DATABASE_URL}}
# Or use individual variables:
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}

# Optional: Auto-run migrations
RUN_MIGRATIONS=1
```

**Deployment Process:**
1. Push to `main` branch
2. GitHub Actions runs smoke test (Docker build validation)
3. Railway detects Dockerfile and builds multi-stage image
4. App starts with nginx + php-fpm via supervisord
5. Site goes live on port 8080

**Railway Configuration:**
- **Builder**: Dockerfile
- **Port**: 8080 (automatically detected)
- **Health Check**: `/up` (optional)
- **Start Command**: Handled by Dockerfile CMD

**What the Docker Build Does:**
1. **Stage 1**: Builds frontend assets with npm (Vite)
2. **Stage 2**: Installs PHP dependencies with Composer (--no-dev)
3. **Stage 3**: Combines everything into PHP-FPM + Nginx runtime
4. **On Start**: Generates APP_KEY if missing, caches config/routes/views, runs migrations if enabled

**Logs:**
All logs go to stderr/stdout for Railway dashboard visibility. Set `LOG_CHANNEL=stderr` in Railway env vars.

**Troubleshooting:**
- Build fails? Check GitHub Actions smoke test results
- 500 errors? Check Railway logs for missing APP_KEY or DB issues
- Assets missing? Verify `public/build/` exists in built image
- DB errors? Ensure Postgres plugin is added and variables are set

See [DEPLOYMENT_GUIDE.md](docs/DEPLOYMENT_GUIDE.md) for detailed instructions and advanced configuration.

## Documentation

For complete documentation, see the **[docs/](docs/)** folder or visit the **[Documentation Index](docs/README.md)**.

### Quick Links

**Essential:**
- **[README](README.md)** (this file) - Project overview
- **[CONTRIBUTING](CONTRIBUTING.md)** - Development workflow
- **[docs/](docs/)** - All documentation

**Planning:**
- **[ROADMAP](docs/ROADMAP.md)** - Development phases
- **[TODO](docs/TODO.md)** - Current tasks

**Technical:**
- **[DEPLOYMENT_GUIDE](docs/DEPLOYMENT_GUIDE.md)** - Railway deployment
- **[PROJECT_STRUCTURE](docs/PROJECT_STRUCTURE.md)** - Codebase organization

**Design:**
- **[BRAND_GUIDELINES](docs/BRAND_GUIDELINES.md)** - Design system
- **[DESIGN_FOUNDATIONS](docs/DESIGN_FOUNDATIONS.md)** - Design philosophy

### Current Phase

**Phase 1 ✅**: Foundation & Infrastructure (Complete)
**Phase 2 🎯**: Browser Games (Next - See [ROADMAP](docs/ROADMAP.md))

## Contributing

This is a personal project, but suggestions and feedback are welcome!

## Development Workflow

- **Main Branch**: Production-ready code (auto-deploys to Railway)
- **Feature Branches**: New features and experiments
- **Commit Convention**: [Conventional Commits](https://www.conventionalcommits.org/)

Example:
```powershell
git checkout -b feature/sudoku-game
# ... make changes ...
git commit -m "feat(games): add sudoku game implementation"
git push origin feature/sudoku-game
```

## License

All rights reserved. This is proprietary software for Ursa Minor Games.

---

**Built with Laravel** | **Deployed on Railway** | **© Ursa Minor Games**
