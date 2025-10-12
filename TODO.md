# Ursa Minor Games - TODO List

**Current Phase**: Phase 0 - Emergency Fixes
**Last Updated**: 2025-10-12

---

## 🚨 CRITICAL (Do First)

### Phase 0: Emergency Fixes

#### Railway Deployment Issue
- [ ] **Investigate Railway build failure**
  - Check Railway dashboard for error logs
  - Review recent commits for breaking changes
  - Verify nixpacks.toml configuration
  - Test environment variables
  - Check PHP version compatibility

- [ ] **Fix deployment**
  - Identify root cause
  - Apply fix
  - Test deployment
  - Verify site loads correctly
  - Document issue and solution

- [ ] **Verify site functionality**
  - Test homepage loads
  - Verify starfield animation
  - Check header scroll behavior
  - Test on mobile devices
  - Check all asset loading

**Exit Criteria**: Site live and working on Railway

---

## 📋 HIGH PRIORITY (This Week)

### Phase 1.1: Documentation Organization
- [x] Create MASTER_ROADMAP.md
- [x] Create FEATURE_EXTRACTION_GUIDE.md
- [x] Create docs/INDEX.md
- [ ] Move appropriate files to docs/ folder
- [ ] Update README.md to reference new structure
- [ ] Create development environment setup guide
- [ ] Document Railway deployment troubleshooting

### Phase 1.2: TALL Stack Setup
- [ ] **Install Node.js**
  - Download LTS version from nodejs.org
  - Install on development machine
  - Verify: `node --version` and `npm --version`
  - Restart terminal/IDE

- [ ] **Install and configure Tailwind CSS**
  ```bash
  npm install -D tailwindcss postcss autoprefixer
  npx tailwindcss init -p
  ```
  - Update tailwind.config.js with Ursa Minor colors
  - Create resources/css/app.css
  - Configure Vite to process Tailwind

- [ ] **Install Alpine.js**
  ```bash
  npm install alpinejs
  ```
  - Configure in resources/js/app.js
  - Test basic Alpine functionality

- [ ] **Complete Livewire 3 setup** (already installed)
  - Verify Livewire is working
  - Create test component
  - Add to homepage

- [ ] **Create color palette CSS variables**
  ```css
  :root {
    --night-black: #000000;
    --midnight-blue: #001a33;
    --evening-blue: #002d58;
    --star-white: #ffffff;
    --star-yellow: #fff89a;
  }
  ```

- [ ] **Start migrating CSS to Tailwind**
  - Don't remove old CSS yet
  - Gradually apply Tailwind classes
  - Test each change

---

## 📅 MEDIUM PRIORITY (This Month)

### Phase 1.3: Component Architecture
- [ ] **Create layout system**
  - Create `resources/views/layouts/app.blade.php`
  - Include head, navigation, content, footer slots
  - Add Livewire and Alpine.js scripts

- [ ] **Extract header to component**
  - Create `resources/views/components/header.blade.php`
  - Move header HTML from welcome.blade.php
  - Add props for customization
  - Test on homepage

- [ ] **Extract footer to component**
  - Create `resources/views/components/footer.blade.php`
  - Move footer HTML
  - Add props for links
  - Test on homepage

- [ ] **Create navigation component**
  - Create `resources/views/components/nav.blade.php`
  - Support active state
  - Mobile responsive
  - Dropdown support (future)

- [ ] **Create game card component**
  - Create `resources/views/components/game-card.blade.php`
  - Display game icon, name, description
  - Link to game
  - "Coming Soon" state

- [ ] **Restructure welcome.blade.php**
  - Rename to `resources/views/pages/home.blade.php`
  - Use new layout
  - Use new components
  - Update route in web.php

### Phase 1.4: Content Pages
- [ ] **Create About page**
  - Explain Ursa Minor vision
  - Board game café dream
  - Current projects overview
  - Future plans
  - Apply night sky branding

- [ ] **Create Contact page**
  - Simple contact information
  - Social media links (future)
  - Email (future)
  - Discord link (future)

- [ ] **Create 404 error page**
  - Branded 404 message
  - Night sky aesthetic
  - Link back to homepage
  - Search functionality (future)

- [ ] **Update navigation**
  - Add all pages to nav
  - Set active states
  - Mobile menu
  - Test all links

- [ ] **SEO improvements**
  - Meta descriptions for all pages
  - Open Graph tags
  - Twitter cards
  - Sitemap
  - robots.txt (already exists)

---

## 🔮 FUTURE (Next Phases)

### Phase 2: Browser Games (Weeks 3-10)
*See MASTER_ROADMAP.md for complete breakdown*
- [ ] Analyze games repository
- [ ] Plan asset migration
- [ ] Extract Sudoku game
- [ ] Create game lobby
- [ ] Migrate 4 more games

### Phase 3: F1 Predictions (Weeks 11-18)
*See MASTER_ROADMAP.md for complete breakdown*
- [ ] Extract F1 models and controllers
- [ ] Migrate database schema
- [ ] Integrate with main site
- [ ] Set up F1 API
- [ ] Prepare for 2026 season

### Phase 4: Board Games (Weeks 19-30)
*See MASTER_ROADMAP.md for complete breakdown*
- [ ] Extract Agency card system
- [ ] Build game engine
- [ ] Create first board game
- [ ] Add playtesting tools

### Phase 5: World-Building Wiki (Weeks 31-40)
*See MASTER_ROADMAP.md for complete breakdown*
- [ ] Build wiki platform
- [ ] Import Lumaria content
- [ ] Add collaboration tools
- [ ] Enable non-programmer editing

---

## 🧪 TESTING (Ongoing)

### Per Feature
- [ ] Write feature tests
- [ ] Write unit tests
- [ ] Manual testing
- [ ] Mobile testing
- [ ] Performance testing

### Before Each Commit
- [ ] Run `php artisan test`
- [ ] Run `./vendor/bin/pint` (code formatting)
- [ ] Check for console errors
- [ ] Test on local environment

### Before Each Deployment
- [ ] All tests passing
- [ ] No linter errors
- [ ] Assets load correctly
- [ ] Mobile responsive
- [ ] Railway build succeeds

---

## 📝 DOCUMENTATION (Ongoing)

### Per Feature
- [ ] Update MASTER_ROADMAP.md
- [ ] Update feature-specific docs
- [ ] Update README.md if needed
- [ ] Add code comments
- [ ] Update TODO.md

### Weekly
- [ ] Review and update TODO.md
- [ ] Update progress in MASTER_ROADMAP.md
- [ ] Document any blockers
- [ ] Document decisions made

### Per Phase
- [ ] Create phase summary document
- [ ] Update success metrics
- [ ] Document lessons learned
- [ ] Celebrate milestone!

---

## 🔧 TECHNICAL DEBT (Track & Address)

### Current Technical Debt
- [ ] CSS split between public/style.css and future Tailwind
- [ ] JavaScript split between public/script.js and future Alpine.js
- [ ] No automated testing yet
- [ ] No CI/CD pipeline
- [ ] Manual deployments only

### Future Technical Debt Prevention
- [ ] Set up CI/CD (GitHub Actions)
- [ ] Automated testing on PRs
- [ ] Code coverage tracking
- [ ] Performance monitoring
- [ ] Error tracking (Sentry)

---

## 🎯 WEEKLY GOALS

### Week of Oct 13-19, 2025

**Monday (Oct 13)**
- [ ] Fix Railway deployment issue
- [ ] Verify site is live
- [ ] Document the fix

**Tuesday (Oct 14)**
- [ ] Install Node.js
- [ ] Install Tailwind CSS
- [ ] Install Alpine.js
- [ ] Verify TALL stack working

**Wednesday (Oct 15)**
- [ ] Create layout system
- [ ] Extract header component
- [ ] Extract footer component
- [ ] Test components

**Thursday (Oct 16)**
- [ ] Create navigation component
- [ ] Create game card component
- [ ] Refactor homepage to use components
- [ ] Test on mobile

**Friday (Oct 17)**
- [ ] Create About page
- [ ] Create Contact page
- [ ] Create 404 page
- [ ] Update navigation

**Saturday (Oct 18)**
- [ ] Code review
- [ ] Testing
- [ ] Documentation updates
- [ ] Deploy to Railway

**Sunday (Oct 19)**
- [ ] Review week's progress
- [ ] Update roadmap
- [ ] Plan next week
- [ ] Rest!

---

## ✅ COMPLETED

### 2025-10-12
- [x] Created MASTER_ROADMAP.md
- [x] Created FEATURE_EXTRACTION_GUIDE.md
- [x] Created docs/INDEX.md
- [x] Created TODO.md
- [x] Analyzed existing repositories
- [x] Documented extraction strategies

### Previous (from PHASE1_PROGRESS.md)
- [x] Replaced lorem ipsum with real content
- [x] Added About section
- [x] Created Coming Soon sections
- [x] Added footer
- [x] Improved SEO meta tags
- [x] Installed Livewire
- [x] Created project structure documentation
- [x] Added Laravel Pint configuration
- [x] Created CONTRIBUTING.md
- [x] Created PROJECT_STRUCTURE.md

---

## 🚫 BLOCKED / NEEDS DECISION

### Decisions Needed
- [ ] **F1 API Choice**: Ergast vs FastF1 vs manual entry
  - Research options
  - Document pros/cons
  - Make decision by Phase 3

- [ ] **User Authentication**: Laravel Breeze vs Jetstream
  - Review features
  - Consider future needs
  - Decide by Phase 3

- [ ] **Caching Solution**: Redis vs Memcached vs File
  - Evaluate Railway options
  - Consider cost
  - Decide by Phase 6

### Blockers
*None currently*

---

## 💡 IDEAS / FUTURE FEATURES

### Nice to Have
- [ ] Dark/Light mode toggle
- [ ] Custom user themes
- [ ] PWA support (offline games)
- [ ] Mobile app (React Native)
- [ ] Discord bot integration
- [ ] Twitch/YouTube integration
- [ ] Tournament system
- [ ] User-generated content
- [ ] Mod support for games
- [ ] API for third-party developers

### Community Features
- [ ] Forums
- [ ] Chat system
- [ ] Friend system
- [ ] Guilds/clans
- [ ] Tournaments
- [ ] Leaderboards
- [ ] Achievements
- [ ] Badges
- [ ] User profiles
- [ ] Activity feeds

### Monetization Ideas (Phase 7)
- [ ] Premium subscriptions
- [ ] Cosmetic items
- [ ] Board game sales
- [ ] Print-on-demand
- [ ] Merchandise
- [ ] Donations/Patreon
- [ ] Ads (tasteful, minimal)
- [ ] Sponsored content

---

## 📊 METRICS TO TRACK

### Development Metrics
- [ ] Time per phase
- [ ] Features completed per week
- [ ] Bugs found vs fixed
- [ ] Test coverage percentage
- [ ] Code quality scores

### User Metrics (Future)
- [ ] Daily active users
- [ ] Monthly active users
- [ ] User retention rate
- [ ] Average session duration
- [ ] Games played per user

### Performance Metrics
- [ ] Page load time
- [ ] Time to interactive
- [ ] Railway build time
- [ ] API response times
- [ ] Error rates

### Business Metrics (Phase 7)
- [ ] Revenue
- [ ] Cost per user
- [ ] Conversion rates
- [ ] Customer lifetime value
- [ ] Return on investment

---

## 🎉 WINS TO CELEBRATE

### When to Celebrate
- [ ] Railway deployment fixed
- [ ] TALL stack fully integrated
- [ ] First game migrated
- [ ] Game lobby launched
- [ ] 100 users milestone
- [ ] F1 season launch
- [ ] First board game completed
- [ ] Wiki launched
- [ ] 1000 users milestone
- [ ] First revenue
- [ ] Full platform complete

### How to Celebrate
- Document the win in roadmap
- Share progress on social media (future)
- Take a break and enjoy the moment
- Plan the next challenge

---

## 📞 CONTACTS / RESOURCES

### Development Resources
- Laravel Docs: https://laravel.com/docs
- Livewire Docs: https://laravel-livewire.com/docs
- Tailwind Docs: https://tailwindcss.com/docs
- Alpine Docs: https://alpinejs.dev/docs

### Hosting Resources
- Railway Dashboard: https://railway.app
- Railway Docs: https://docs.railway.app

### Repository Locations
- Current repo: `C:\Users\bearj\Herd\website\`
- F1 repo: `C:\Users\bearj\Herd\formula1predictions\`
- Games repo: `C:\Users\bearj\Herd\games\`
- T&T repo: `C:\Users\bearj\Herd\tavernsandtreasures\`
- Agency repo: `C:\Users\bearj\Herd\agency\`

---

**Remember**: One task at a time. "Eat the elephant one bite at a time." 🐘

**Update this file**: After completing tasks, after each day, and when priorities change.

**Review weekly**: Ensure you're on track and adjust as needed.

---

*Last updated: 2025-10-12*
*Next review: 2025-10-13*

