# Ursa Minor Games - Documentation Index

Welcome to the Ursa Minor Games documentation! This index will help you find the information you need.

---

## 🎯 Start Here

**New to the Project?**
1. Read the [README](../README.md) - Project overview and quick start
2. Read the [Master Roadmap](MASTER_ROADMAP.md) - Complete vision and plan
3. Check the [Brand Guidelines](../BRAND_GUIDELINES.md) - Visual identity and design

**Ready to Contribute?**
1. Read the [Contributing Guide](../CONTRIBUTING.md) - Development workflow
2. Check the [Project Structure](../PROJECT_STRUCTURE.md) - File organization
3. Review the [Feature Extraction Guide](FEATURE_EXTRACTION_GUIDE.md) - How to integrate features

**Deploying?**
1. Read the [Railway Setup Guide](../RAILWAY_SETUP.md) - Quick deployment
2. Review the [Deployment Guide](../DEPLOYMENT.md) - Detailed instructions

---

## 📚 Core Documentation

### Planning & Vision
- **[Master Roadmap](MASTER_ROADMAP.md)** - Complete project roadmap with phases, milestones, and timelines
- **[README](../README.md)** - Project overview, tech stack, and current features
- **[Phase 1 Progress](../PHASE1_PROGRESS.md)** - Current progress tracking

### Design & Branding
- **[Brand Guidelines](../BRAND_GUIDELINES.md)** - Visual identity, colors, typography, and design principles
- **[Project Structure](../PROJECT_STRUCTURE.md)** - File organization and naming conventions

### Development
- **[Contributing Guide](../CONTRIBUTING.md)** - Development workflow and standards
- **[Feature Extraction Guide](FEATURE_EXTRACTION_GUIDE.md)** - How to integrate features from other repos
- **[Implementation Summary](../IMPLEMENTATION_SUMMARY.md)** - What's been built so far

### Deployment
- **[Railway Setup](../RAILWAY_SETUP.md)** - Quick deployment guide
- **[Deployment Guide](../DEPLOYMENT.md)** - Detailed deployment instructions

---

## 🎮 Feature-Specific Documentation

### Browser Games (Phase 2)
*Coming soon after games integration*
- Game Development Guide
- Asset Management Guide
- Game Lobby Documentation
- Individual Game Guides

### F1 Predictions (Phase 3)
*Coming soon after F1 integration*
- F1 System Overview
- Scoring System Documentation
- API Integration Guide
- Admin Panel Guide

### Board Games (Phase 4)
*Coming soon after board game integration*
- Board Game Platform Guide
- Card Management System
- Playtesting Tools Documentation
- Print & Play Guide

### World-Building Wiki (Phase 5)
*Coming soon after wiki integration*
- Wiki Platform Guide
- Content Creation Guide
- Collaboration Tools Documentation
- Lumaria World Guide

---

## 🛠️ Technical Documentation

### Architecture
- **[Project Structure](../PROJECT_STRUCTURE.md)** - Directory organization
- **Tech Stack**: Laravel 12, Livewire 3, Alpine.js, Tailwind CSS

### Development Environment
- **Local Development**: Laravel Herd at http://website.test/
- **Node.js**: Required for Tailwind and Alpine
- **Database**: SQLite (development), MySQL (production)

### Testing
*Documentation coming soon*
- Testing Strategy
- Test Coverage Requirements
- Writing Tests Guide

### Performance
*Documentation coming soon*
- Performance Optimization Guide
- Caching Strategy
- Asset Optimization

---

## 📖 Reference Documentation

### External Repositories
Documentation for features to be extracted:

#### formula1predictions
Location: `C:\Users\bearj\Herd\formula1predictions\`
- [README](../../formula1predictions/README.md)
- Status: Nearly complete, ready to integrate
- Key Features: Predictions, leaderboards, real-time notifications

#### games
Location: `C:\Users\bearj\Herd\games\`
- [README](../../games/README.md)
- [Architecture](../../games/ARCHITECTURE.md)
- [Game Development Guide](../../games/GAME_DEVELOPMENT_GUIDE.md)
- [Asset Usage Guide](../../games/ASSET_USAGE_GUIDE.md)
- Status: 25+ games ready to extract

#### tavernsandtreasures
Location: `C:\Users\bearj\Herd\tavernsandtreasures\`
- [Game Bible](../../tavernsandtreasures/GAME_BIBLE.md)
- [Game Design Document](../../tavernsandtreasures/GAME_DESIGN_DOCUMENT.md)
- Status: Complete world-building content

#### agency
Location: `C:\Users\bearj\Herd\agency\`
- [README](../../agency/README.md)
- Status: Board game prototyping platform

### Laravel Resources
- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://laravel-livewire.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

### Design Resources
- [Heroicons](https://heroicons.com) - Recommended icon set
- [Google Fonts - Oswald](https://fonts.google.com/specimen/Oswald) - Primary font
- [Railway Documentation](https://docs.railway.app)

---

## 🎯 Quick Reference

### Common Tasks

**Starting Development**
```bash
# Start local server (with Herd)
# Visit http://website.test/

# Or with artisan
php artisan serve

# Start Vite dev server (for asset hot reload)
npm run dev
```

**Running Tests**
```bash
php artisan test
```

**Code Formatting**
```bash
./vendor/bin/pint
```

**Creating Components**
```bash
php artisan make:livewire ComponentName
php artisan make:component ComponentName
```

**Database Operations**
```bash
php artisan migrate
php artisan migrate:rollback
php artisan db:seed
```

**Deployment**
```bash
# Commit and push to main branch
git add .
git commit -m "feat: description"
git push origin main

# Railway auto-deploys!
```

### File Locations

```
website/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Models/              # Eloquent models
│   ├── Livewire/           # Livewire components
│   └── Services/           # Business logic
├── database/
│   ├── migrations/         # Database schema
│   └── seeders/           # Test data
├── public/
│   ├── style.css          # Main stylesheet (temp, moving to Tailwind)
│   ├── script.js          # Starfield animation
│   └── scroll.js          # Header scroll behavior
├── resources/
│   ├── views/             # Blade templates
│   ├── css/               # Tailwind CSS (future)
│   └── js/                # Alpine.js (future)
├── routes/
│   └── web.php           # Application routes
├── tests/
│   ├── Feature/          # Feature tests
│   └── Unit/             # Unit tests
└── docs/
    ├── INDEX.md          # This file
    ├── MASTER_ROADMAP.md # Complete roadmap
    └── *.md              # Other documentation
```

### Color Palette Quick Reference

```css
/* Primary Colors */
--night-black: #000000;
--midnight-blue: #001a33;
--evening-blue: #002d58;
--star-white: #ffffff;
--star-yellow: #fff89a;

/* Opacity Variations */
--dark-overlay: rgba(0, 0, 0, 0.3);
--light-overlay: rgba(255, 255, 255, 0.05);
--muted-text: rgba(255, 255, 255, 0.7);
--subtle-accent: rgba(255, 248, 154, 0.3);
```

### Conventional Commits Quick Reference

```
feat: New feature
fix: Bug fix
docs: Documentation only
style: Code formatting (not CSS)
refactor: Code restructuring
perf: Performance improvements
test: Test additions/updates
chore: Maintenance tasks
```

---

## 🔄 Keeping Documentation Updated

### Documentation Standards
- All documentation in `docs/` folder
- Use Markdown (.md) for human-readable docs
- Use .mdc for cursor-specific documentation
- Update INDEX.md when adding new docs
- Keep roadmap updated weekly
- Document all major decisions

### When to Update Docs

**After Each Feature**
- Update MASTER_ROADMAP.md progress
- Add feature-specific documentation
- Update README.md if needed
- Add to relevant guides

**After Each Phase**
- Review and update all documentation
- Archive completed phase docs
- Update success metrics
- Celebrate milestone!

**Weekly**
- Review MASTER_ROADMAP.md
- Update current progress
- Adjust priorities if needed
- Document blockers

---

## 🆘 Getting Help

### Internal Resources
1. Check this documentation index
2. Read the relevant guide
3. Check the source repository docs
4. Review existing code for patterns

### External Resources
1. [Laravel Docs](https://laravel.com/docs)
2. [Livewire Docs](https://laravel-livewire.com/docs)
3. [Railway Docs](https://docs.railway.app)
4. [Stack Overflow](https://stackoverflow.com/)

### Community
*Coming soon*
- Discord Server
- GitHub Discussions
- Reddit Community

---

## 📝 Contributing to Documentation

Found an error? Want to improve the docs?

1. Create a feature branch
```bash
git checkout -b docs/improve-documentation
```

2. Make your changes

3. Commit with conventional commits
```bash
git commit -m "docs: improve feature extraction guide"
```

4. Push and create PR
```bash
git push origin docs/improve-documentation
```

---

## 🗺️ Documentation Roadmap

### Phase 1 (Current)
- [x] Master Roadmap
- [x] Feature Extraction Guide
- [x] Documentation Index
- [ ] Development Environment Setup Guide
- [ ] Troubleshooting Guide

### Phase 2 (Browser Games)
- [ ] Game Development Guide
- [ ] Asset Management Guide
- [ ] Game Testing Guide
- [ ] Individual Game Guides

### Phase 3 (F1 Predictions)
- [ ] F1 System Overview
- [ ] API Integration Guide
- [ ] Scoring System Documentation
- [ ] Admin Guide

### Phase 4 (Board Games)
- [ ] Board Game Platform Guide
- [ ] Card System Documentation
- [ ] Playtesting Guide
- [ ] Manufacturing Guide

### Phase 5 (Wiki)
- [ ] Wiki Platform Guide
- [ ] Content Creation Guide
- [ ] Collaboration Guide
- [ ] Lumaria World Guide

### Phase 6 (Polish)
- [ ] User Guide
- [ ] Admin Guide
- [ ] API Documentation
- [ ] Deployment Guide (advanced)

---

## 📌 Version History

- **2025-10-12**: Initial documentation structure created
  - Master Roadmap established
  - Feature Extraction Guide created
  - Documentation Index created
  - Existing docs organized

---

*This documentation is a living project. As Ursa Minor Games grows, so will these docs. Keep them updated, keep them useful, and keep building amazing experiences!*

**Built under the stars** | **© Ursa Minor Games**

