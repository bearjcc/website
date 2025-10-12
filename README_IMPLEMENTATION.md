# Ursa Minor - Laravel 11 + TALL Stack Implementation

## Overview

This is a complete Laravel 11 application using the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire v3) built for Ursa Minor - a browser games and creative projects platform.

## Features Implemented

### Authentication & Authorization

- **Livewire-based Auth**: Custom auth system with Login, Register, and Logout components
- **Role-based Access Control**: Three roles - guest, contributor, admin
- **Policies & Gates**: Authorization for Lore and Admin sections
- **Invisible Private Routes**: Lore section returns 404 to guests (not just unauthorized)

### Data Models

All models created with migrations and relationships:

- **Users**: With role field (guest/contributor/admin)
- **Games**: Browser games with slug, status, rules, options
- **Scores**: Game leaderboards with player tracking
- **Posts**: Blog system with markdown support
- **Lore Pages**: Private contributor-only wiki pages
- **Novellas**: Story content with file attachments
- **Feature Blocks**: Homepage feature management

### Routes & Pages

**Public Routes:**
- `/` - Home page with hero and feature tiles
- `/games` - Published games index
- `/games/{slug}` - Individual game play page
- `/blog` - Blog posts index
- `/blog/{slug}` - Individual post
- `/about` - About page

**Auth Routes:**
- `/login` - Login page
- `/register` - Registration page

**Contributor Routes** (hidden from guests):
- `/lore` - Lore library index
- `/lore/create` - Create new lore page
- `/lore/{slug}` - View lore page
- `/lore/{slug}/edit` - Edit lore page

**Admin Routes:**
- `/admin/features` - Manage homepage feature blocks

### Design System - "Starlight"

**Color Palette:**
- Night Black: `#000000`
- Midnight Blue: `#001a33`
- Evening Blue: `#002d58`
- Star White: `#ffffff`
- Star Yellow: `#fff89a` (accents)

**Components:**
- Translucent cards with backdrop blur
- Night sky gradient background
- Starfield canvas animation
- Custom focus rings with yellow accent
- Responsive max-width 900px centered content

**Typography:**
- Font: Oswald from Google Fonts
- Clean hierarchy with yellow headings
- Readable contrast throughout

### Layouts

1. **app.blade.php** - Main public layout with sticky nav and starfield
2. **private.blade.php** - Contributor area layout
3. **public-card.blade.php** - Reusable card component

### Seeded Data

The database seeder creates:
- 3 users (admin, contributor, guest) - password: `password`
- 3 games (Tic-Tac-Toe published, Sudoku/Minesweeper draft)
- 2 published blog posts
- 1 feature block pointing to Tic-Tac-Toe

### Deployment Configuration

**Railway Setup:**
- `nixpacks.toml` - Auto-build configuration
- `Procfile` - Alternative deployment config
- `Dockerfile` - Optional Docker deployment
- `DEPLOYMENT_GUIDE.md` - Complete deployment documentation

**Environment Support:**
- SQLite for local development
- PostgreSQL for production
- Automatic migrations on deploy

## Quick Start

### Local Development

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations and seed
php artisan migrate:fresh --seed

# Start development server
npm run dev
php artisan serve
```

Access at: `http://localhost:8000`

### Test Accounts

- **Admin**: admin@ursaminor.test / password
- **Contributor**: contributor@ursaminor.test / password
- **Guest**: guest@ursaminor.test / password

## Project Structure

```
app/
├── Livewire/
│   ├── Auth/           # Auth components
│   └── Pages/          # Page components
├── Models/             # Eloquent models
└── Policies/           # Authorization policies

resources/
├── css/
│   └── app.css        # Tailwind + custom styles
├── js/
│   └── app.js         # Alpine.js setup
└── views/
    ├── components/    # Blade components
    ├── layouts/       # Layout files
    └── livewire/      # Livewire views

database/
├── migrations/        # Database schema
└── seeders/          # Sample data

routes/
└── web.php           # All route definitions
```

## Key Technologies

- **Laravel 11** - PHP framework
- **Livewire 3** - Full-stack framework
- **Alpine.js** - Lightweight JavaScript
- **Tailwind CSS 4** - Utility-first CSS
- **Blade Heroicons** - SVG icon library
- **Laravel Pint** - Code style fixer

## Design Decisions

### Laptop/Tablet First

- Max content width: 900px
- Responsive grid layouts
- Touch-friendly UI elements
- Mobile-optimized navigation

### Starfield Theme

- Deep blue gradient background
- Animated starfield canvas
- Translucent UI elements
- Yellow accent color for interactivity
- No purple or typical "AI gradients"

### Authorization Strategy

- Simple role field on users (no complex pivot tables)
- Policy-based authorization for models
- Gate-based authorization for sections
- 404 responses for unauthorized access to keep sections invisible

### Content Management

- Markdown support for all long-form content
- Draft/Published status on content models
- Feature blocks for homepage curation
- Slug-based routing for SEO

## Next Steps

### Integration Points

The GamePlay page (`/games/{slug}`) includes a canvas area ready for:
- Existing Livewire game components
- Integration via dynamic component loading
- Example: `<livewire:games.tic-tac-toe :game="$game" />`

### Recommended Enhancements

1. **Markdown Rendering**: Add a package like `league/commonmark`
2. **User Profiles**: Expand user model with avatars, bios
3. **Lore Search**: Full-text search for contributor area
4. **Game Integration**: Connect existing game components
5. **RSS Feeds**: Add RSS for blog posts
6. **Sitemap**: Auto-generate XML sitemap
7. **Admin Dashboard**: Stats and analytics

### Testing

```bash
# Run tests
php artisan test

# Code style
./vendor/bin/pint

# Check for issues
php artisan route:list
php artisan config:clear
```

## Acceptance Criteria - Met ✓

- ✓ Home shows hero + feature tiles from feature_blocks
- ✓ Links to Games/Blog/About (no mention of physical shop)
- ✓ Games index lists only published games
- ✓ Each game card has "Play" CTA
- ✓ GamePlay renders empty canvas area for game injection
- ✓ Lore routes return 404 to guests
- ✓ Contributors can create/edit lore pages
- ✓ Dark "night sky" styling with readable contrast
- ✓ Centered content max-width 900px
- ✓ Sticky top navigation
- ✓ Role-based authorization (guest/contributor/admin)
- ✓ All models and migrations created
- ✓ Seeders with sample data
- ✓ Railway deployment config

## Support

For questions or issues:
- Check `DEPLOYMENT_GUIDE.md` for deployment help
- Review Laravel docs: https://laravel.com/docs
- Livewire docs: https://livewire.laravel.com
- Tailwind CSS docs: https://tailwindcss.com

## License

This is a proprietary project for Ursa Minor.

