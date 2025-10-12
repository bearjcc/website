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

- Animated starfield background with blinking stars
- Ursa Minor branding with smooth scroll animations
- Responsive design for all screen sizes
- Fast, simple, and elegant night sky aesthetic

## Tech Stack

- **Framework**: Laravel 12.x
- **PHP**: 8.3+
- **Hosting**: Railway
- **Frontend**: HTML, CSS, JavaScript (vanilla)
- **Future**: TALL Stack (Tailwind, Alpine.js, Laravel, Livewire)

## Development

### Prerequisites

- PHP 8.3 or higher
- Composer
- Git

### Local Setup

```powershell
# Clone the repository
git clone https://github.com/bearjcc/website.git
cd website

# Install dependencies
composer install

# Set up environment
Copy-Item -Path ".env.example" -Destination ".env"
php artisan key:generate

# Run local development server
php artisan serve
```

Visit http://localhost:8000 to view the site.

### Project Structure

```
website/
├── app/                 # Application logic
├── public/             # Static assets (CSS, JS, SVGs)
│   ├── bear.svg       # Ursa Minor logo
│   ├── GRADIENT BG.svg
│   ├── style.css      # Main styles
│   ├── script.js      # Starfield animation
│   └── scroll.js      # Header scroll behavior
├── resources/
│   └── views/
│       └── welcome.blade.php  # Homepage
├── routes/
│   └── web.php        # Application routes
├── tests/              # Test suite
│   ├── Feature/       # Feature/integration tests
│   └── Unit/          # Unit tests
└── nixpacks.toml      # Railway deployment config
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

See [DEPLOYMENT.md](./DEPLOYMENT.md) for detailed Railway deployment instructions.

Quick deploy:
1. Push to `main` branch
2. Railway auto-deploys via webhook
3. Site goes live automatically

## Documentation

**📚 Complete documentation is now available in the `docs/` folder:**

- **[Master Roadmap](docs/MASTER_ROADMAP.md)** - Complete project vision and plan
- **[Documentation Index](docs/INDEX.md)** - All documentation organized
- **[Feature Extraction Guide](docs/FEATURE_EXTRACTION_GUIDE.md)** - How to integrate features
- **[TODO List](TODO.md)** - Current tasks and priorities

### Quick Phase Overview

**Phase 0 (Current)**: Emergency fixes - Railway deployment ✅
**Phase 1 (Weeks 1-2)**: Foundation & Infrastructure - TALL stack, components
**Phase 2 (Weeks 3-10)**: Browser Games - 5-10 classic games
**Phase 3 (Weeks 11-18)**: F1 Predictions - Full system from formula1predictions repo
**Phase 4 (Weeks 19-30)**: Board Game Platform - Digital prototyping system
**Phase 5 (Weeks 31-40)**: World-Building Wiki - Collaborative lore system

See [Master Roadmap](docs/MASTER_ROADMAP.md) for complete details.

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
