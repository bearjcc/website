# Ursa Minor Games

A Laravel (TALL) app for the **Ursa Minor Games** site: night-sky marketing pages, about, auth, contributor lore, Sudoku and Letter Walker score APIs, and **301 redirects** to the public **small games** host. In production, those games use **path URLs** on the apex (for example `https://ursaminor.games/sudoku`). The **Taverns and Treasures** RPG is a **separate** product on `https://taverns.ursaminor.games`. This repo still contains Livewire game components and tests used in the pipeline and for integration.

## About

Ursa Minor is a gaming brand focused on:
- **Browser games**: Free-to-play classics; public play is on the host set by `GAMES_BASE_URL` (see [docs/GAME_DEVELOPMENT_GUIDE.md](docs/GAME_DEVELOPMENT_GUIDE.md)).
- **F1 predictions**: Sister experience (separate local and production URL; see the home “sister sites” grid and `config/ursa_sites.php`).
- **Board games and video work**: Ongoing and announced when ready.

This repository is the **main marketing site and supporting APIs**, not a single build that hosts every product at the same origin.

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

Visit http://website.test/ (Laravel Herd project name; Herd serves the site automatically).

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
├── tests/             # PHPUnit (run `php artisan test`)
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

**Three core documents**:
1. **[README.md](README.md)** (this file) — Project overview, setup, development
2. **[DESIGN_BIBLE.md](DESIGN_BIBLE.md)** — Complete design reference
3. **[TODO.md](docs/TODO.md)** — Current tasks and roadmap

**Technical guides** (when needed):
- **[DEPLOYMENT_GUIDE](docs/DEPLOYMENT_GUIDE.md)** — Railway deployment
- **[FEATURE_EXTRACTION_GUIDE](docs/FEATURE_EXTRACTION_GUIDE.md)** — Integrate from other repos
- **[docs/](docs/)** — Full index

**AI guidance** in `AGENTS.md` (root); optional `.cursor/rules/*.mdc` if present

### Current Status

**Phase 1** Foundation: homepage, design system, deployment, Herd and Railway.
**Phase 2** Browser games: many titles ship on the small-games host; this repo may still run engines in Livewire for build and test, while HTTP game routes on this app redirect to `GAMES_BASE_URL`.
**Next** [docs/TODO.md](docs/TODO.md) and your usual tracker; [docs/ROADMAP.md](docs/ROADMAP.md) for the high-level split.

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
