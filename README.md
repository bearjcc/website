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

- ✨ Animated starfield background
- 🐻 Ursa Minor branding with animated header
- 📱 Responsive design
- 🚀 Fast, simple, and elegant

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
└── nixpacks.toml      # Railway deployment config
```

## Deployment

See [DEPLOYMENT.md](./DEPLOYMENT.md) for detailed Railway deployment instructions.

Quick deploy:
1. Push to `main` branch
2. Railway auto-deploys via webhook
3. Site goes live automatically

## Roadmap

### Phase 1: Homepage (Current)
- ✅ Basic Laravel setup
- ✅ Static homepage with animations
- ✅ Railway deployment

### Phase 2: Browser Games
- [ ] Sudoku game
- [ ] Chess game
- [ ] Game lobby/navigation

### Phase 3: F1 Predictions
- [ ] User authentication
- [ ] Race predictions system
- [ ] Leaderboards
- [ ] Points calculation

### Phase 4: Board Games
- [ ] Digital board game platform
- [ ] Rules engine
- [ ] Multiplayer support

### Phase 5: Video Game Content
- [ ] World-building wiki
- [ ] Lore documentation
- [ ] Asset gallery
- [ ] Development blog

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
