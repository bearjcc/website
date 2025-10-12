# Ursa Minor Games - Project Structure

This document explains how the project is organized and where to find/add things.

## Directory Overview

```
website/
├── app/                    # Application core
├── bootstrap/              # Framework bootstrap
├── config/                 # Configuration files
├── database/               # Database migrations & seeders
├── public/                 # Publicly accessible files
├── resources/              # Views, assets, lang files
├── routes/                 # Application routes
├── storage/                # App storage (logs, cache, uploads)
├── tests/                  # Automated tests
└── vendor/                 # Composer dependencies (gitignored)
```

## Key Directories

### `/app` - Application Logic

#### `/app/Http/Controllers`
Contains controllers that handle requests.

**Current:**
- `Controller.php` - Base controller

**Future:**
- `GameController.php` - Browser games
- `F1Controller.php` - F1 predictions
- `BoardGameController.php` - Board games

#### `/app/Models`
Eloquent models representing database tables.

**Current:**
- `User.php` - User model (default Laravel)

**Future:**
- `Game.php` - Browser game records
- `F1Prediction.php` - F1 prediction entries
- `Leaderboard.php` - Leaderboard entries

#### `/app/Providers`
Service providers for dependency injection and bootstrapping.

### `/resources` - Frontend Resources

#### `/resources/views`
Blade templates for HTML output.

**Current:**
- `welcome.blade.php` - Homepage

**Recommended Structure:**
```
views/
├── layouts/
│   ├── app.blade.php           # Main layout
│   └── game.blade.php          # Game layout
├── components/
│   ├── header.blade.php        # Site header
│   ├── footer.blade.php        # Site footer
│   ├── nav.blade.php           # Navigation
│   └── game-card.blade.php     # Game preview card
├── pages/
│   ├── home.blade.php          # Homepage (rename welcome)
│   ├── about.blade.php         # About page
│   └── contact.blade.php       # Contact page
├── games/
│   ├── index.blade.php         # Games lobby
│   ├── sudoku.blade.php        # Sudoku game
│   └── chess.blade.php         # Chess game
├── f1/
│   ├── dashboard.blade.php     # F1 main page
│   ├── predict.blade.php       # Make predictions
│   └── leaderboard.blade.php   # Leaderboard
└── board-games/
    ├── index.blade.php         # Board games catalog
    └── play.blade.php          # Play board game
```

#### `/resources/css`
Future location for Tailwind CSS and custom styles.

#### `/resources/js`
Frontend JavaScript (future Alpine.js, etc.).

### `/public` - Static Assets

**Current:**
- `index.php` - Entry point (Laravel, don't modify)
- `style.css` - Main stylesheet
- `script.js` - Starfield animation
- `scroll.js` - Header scroll behavior
- `bear.svg` - Ursa Minor logo
- `GRADIENT BG.svg` - Background gradient

**Recommended Structure:**
```
public/
├── assets/
│   ├── images/
│   │   ├── logos/
│   │   ├── games/
│   │   └── backgrounds/
│   ├── fonts/
│   └── icons/
├── css/                    # Compiled CSS (future)
├── js/                     # Compiled JS (future)
└── [existing files]
```

### `/routes` - Application Routes

#### `routes/web.php`
Web routes for the application.

**Current:**
```php
Route::get('/', function () {
    return view('welcome');
});
```

**Future Structure:**
```php
// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Games
Route::prefix('games')->name('games.')->group(function () {
    Route::get('/', [GameController::class, 'index'])->name('index');
    Route::get('/sudoku', [GameController::class, 'sudoku'])->name('sudoku');
    Route::get('/chess', [GameController::class, 'chess'])->name('chess');
});

// F1 Predictions
Route::prefix('f1')->name('f1.')->middleware('auth')->group(function () {
    Route::get('/', [F1Controller::class, 'dashboard'])->name('dashboard');
    Route::get('/predict', [F1Controller::class, 'predict'])->name('predict');
    Route::post('/predict', [F1Controller::class, 'store'])->name('store');
    Route::get('/leaderboard', [F1Controller::class, 'leaderboard'])->name('leaderboard');
});

// Board Games
Route::prefix('board-games')->name('board-games.')->group(function () {
    Route::get('/', [BoardGameController::class, 'index'])->name('index');
    Route::get('/{game}', [BoardGameController::class, 'show'])->name('show');
});
```

### `/database` - Database

#### `/database/migrations`
Database migrations for version control of schema.

**Naming Convention:**
```
YYYY_MM_DD_HHMMSS_create_table_name_table.php
```

**Future Migrations:**
- `create_games_table` - Browser games
- `create_f1_predictions_table` - F1 predictions
- `create_leaderboards_table` - Leaderboards
- `create_board_games_table` - Board games

#### `/database/seeders`
Database seeders for populating test data.

### `/tests` - Testing

#### `/tests/Feature`
Feature/integration tests.

**Future Tests:**
- `HomepageTest.php` - Homepage rendering
- `GameTest.php` - Game functionality
- `F1PredictionTest.php` - F1 system
- `LeaderboardTest.php` - Leaderboard calculation

#### `/tests/Unit`
Unit tests for isolated functionality.

## Configuration Files

### Laravel Configuration (`/config`)
- `app.php` - Application settings
- `database.php` - Database connections
- `cache.php` - Cache configuration
- `session.php` - Session management

### Project Configuration (root)
- `.env` - Environment variables (gitignored)
- `.env.example` - Environment template
- `composer.json` - PHP dependencies
- `package.json` - JavaScript dependencies (future)
- `vite.config.js` - Vite configuration (future)
- `pint.json` - Code style configuration
- `phpunit.xml` - Testing configuration

### Deployment
- `nixpacks.toml` - Railway build configuration
- `.gitignore` - Git ignore rules

### Documentation
- `README.md` - Project overview
- `ROADMAP.md` - Development roadmap
- `CONTRIBUTING.md` - Contribution guidelines
- `DEPLOYMENT.md` - Deployment instructions
- `RAILWAY_SETUP.md` - Railway quick start
- `PROJECT_STRUCTURE.md` - This file

## Asset Organization

### Images
```
public/assets/images/
├── logos/
│   ├── ursa-minor-logo.svg
│   └── ursa-minor-icon.svg
├── games/
│   ├── sudoku-preview.png
│   └── chess-preview.png
├── backgrounds/
│   └── gradient-bg.svg
└── misc/
    └── favicon.ico
```

### Styles (Future: Tailwind)
```
resources/css/
├── app.css                 # Main CSS file
└── components/             # Component-specific styles
```

### Scripts
```
resources/js/
├── app.js                  # Main JS file
├── components/             # Component scripts
│   ├── starfield.js       # Starfield animation
│   └── header-scroll.js   # Header behavior
└── games/                  # Game logic
    ├── sudoku.js
    └── chess.js
```

## Naming Conventions

### Controllers
- Singular nouns: `GameController`, not `GamesController`
- Action methods: `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`

### Models
- Singular: `Game`, `User`, `Prediction`
- PascalCase: `F1Prediction`, not `f1_prediction`

### Views
- kebab-case: `game-card.blade.php`
- Match route names: `games.index` → `games/index.blade.php`

### Routes
- Use named routes: `->name('games.index')`
- URL segments: lowercase with hyphens: `/board-games`

### Database
- Tables: plural, snake_case: `games`, `f1_predictions`
- Columns: snake_case: `user_id`, `created_at`
- Foreign keys: `{model}_id`: `user_id`, `game_id`

### CSS Classes
- kebab-case: `.game-card`, `.nav-link`
- BEM for components: `.game-card__title`, `.game-card--featured`

### JavaScript
- camelCase: `const gameScore = 0;`
- Functions: verb + noun: `calculateScore()`, `renderBoard()`

## Component Structure (Future)

### Blade Components
```php
// Create component
php artisan make:component GameCard

// Usage in views
<x-game-card :game="$game" />
```

### Livewire Components (Future)
```php
// Create Livewire component
php artisan make:livewire SudokuBoard

// Usage in views
<livewire:sudoku-board />
```

## Adding New Features

### Checklist for New Feature
1. Create feature branch: `feature/feature-name`
2. Create migration if needed: `php artisan make:migration`
3. Create model if needed: `php artisan make:model`
4. Create controller: `php artisan make:controller`
5. Add routes in `routes/web.php`
6. Create views in `resources/views`
7. Write tests in `tests/Feature`
8. Update documentation
9. Create pull request

## File Naming Quick Reference

| Type | Example | Location |
|------|---------|----------|
| Controller | `GameController.php` | `app/Http/Controllers/` |
| Model | `Game.php` | `app/Models/` |
| Migration | `2024_01_01_create_games_table.php` | `database/migrations/` |
| Seeder | `GameSeeder.php` | `database/seeders/` |
| View | `index.blade.php` | `resources/views/games/` |
| Component | `game-card.blade.php` | `resources/views/components/` |
| Test | `GameTest.php` | `tests/Feature/` |
| CSS | `game-card.css` | `resources/css/components/` |
| JS | `sudoku.js` | `resources/js/games/` |

## Next Steps

1. **Phase 1.2**: Install TALL stack
   - Add Tailwind CSS
   - Add Alpine.js
   - Add Livewire
   - Migrate styles incrementally

2. **Phase 1.3**: Restructure views
   - Create layout files
   - Extract header/footer to components
   - Move welcome.blade.php to pages/home.blade.php

3. **Phase 2.1**: Begin browser games
   - Set up games directory structure
   - Create game models and migrations
   - Build first game (Sudoku)

---

**Keep this document updated as the project evolves!**

