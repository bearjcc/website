# Ursa Minor Games - Development Roadmap

## Vision
Build a comprehensive gaming platform that serves as:
- A portfolio for game development reputation
- A testing ground for game mechanics and world-building
- A community hub for F1 predictions
- A future storefront for board game designs
- A foundation for an ambitious video game project

## Current Status: Phase 0 Complete ✅
- Laravel homepage deployed
- GitHub repository established
- Railway deployment configured
- Basic branding and animations working

---

## Phase 1: Foundation & Infrastructure (Current Priority)

### 1.1 Content & Branding
- [x] Replace lorem ipsum with actual mission statement
- [ ] Add "About" section explaining Ursa Minor vision
- [ ] Create "Coming Soon" sections for Games, F1, Board Games
- [ ] Add footer with links and copyright
- [ ] Improve SEO meta tags

### 1.2 TALL Stack Setup
- [ ] Install and configure Tailwind CSS
- [ ] Install Alpine.js for enhanced interactivity
- [ ] Install Livewire for dynamic components
- [ ] Migrate existing styles to Tailwind (gradually)
- [ ] Create base layout template

### 1.3 Project Structure
- [ ] Create component library (Blade components)
- [ ] Set up proper layouts system
- [ ] Organize assets (images, icons, fonts)
- [ ] Create reusable partials (header, footer, nav)
- [ ] Set up testing framework

### 1.4 Developer Experience
- [ ] Add Laravel Pint configuration
- [ ] Set up pre-commit hooks
- [ ] Create development guidelines document
- [ ] Add VS Code workspace settings
- [ ] Create component generator commands

**Branch**: `feature/phase1-foundation`
**Estimated Time**: 1-2 weeks
**Priority**: High

---

## Phase 2: Browser Games Platform (3-6 months)

### 2.1 Games Infrastructure
- [ ] Create games database schema
- [ ] Build game lobby/directory page
- [ ] Design game card component
- [ ] Implement game state management
- [ ] Add user progress tracking (optional auth)

### 2.2 Game: Sudoku
- [ ] Design Sudoku UI with Livewire
- [ ] Implement puzzle generation algorithm
- [ ] Add difficulty levels (Easy, Medium, Hard, Expert)
- [ ] Create hint system
- [ ] Add timer and move counter
- [ ] Implement save/resume functionality
- [ ] Add daily challenge feature

### 2.3 Game: Chess
- [ ] Build chess board component
- [ ] Implement chess rules engine
- [ ] Add move validation
- [ ] Create AI opponent (basic, medium, hard)
- [ ] Add move history and replay
- [ ] Implement algebraic notation
- [ ] Add puzzle mode (Chess puzzles)

### 2.4 Additional Classic Games
- [ ] Minesweeper
- [ ] 2048
- [ ] Solitaire
- [ ] Tic-tac-toe (with AI)

### 2.5 Leaderboards & Achievements
- [ ] Global leaderboards per game
- [ ] Achievement system
- [ ] User profiles (guest mode available)
- [ ] Statistics and analytics

**Branch**: `feature/browser-games`
**Estimated Time**: 3-6 months
**Priority**: High

---

## Phase 3: F1 Predictions System (2-4 months)

### 3.1 User System
- [ ] Install Laravel Breeze/Jetstream
- [ ] User registration and login
- [ ] Profile management
- [ ] Email verification
- [ ] Password reset functionality

### 3.2 F1 Data Integration
- [ ] Research F1 API options (Ergast, FastF1)
- [ ] Set up data fetching system
- [ ] Store races, drivers, teams data
- [ ] Update schedule automatically
- [ ] Historical data management

### 3.3 Predictions Core
- [ ] Create prediction submission form
- [ ] Implement prediction deadline logic
- [ ] Build points calculation system
- [ ] Create prediction history view
- [ ] Add prediction editing (before deadline)

### 3.4 Leaderboards & Social
- [ ] Overall season leaderboard
- [ ] Race-by-race leaderboards
- [ ] Friend/league system
- [ ] Prediction insights and statistics
- [ ] Social sharing features

### 3.5 Admin Panel
- [ ] Race result entry interface
- [ ] Points recalculation tools
- [ ] User management
- [ ] Season management
- [ ] Announcement system

**Branch**: `feature/f1-predictions`
**Estimated Time**: 2-4 months
**Priority**: Medium-High

---

## Phase 4: Board Game Platform (6-12 months)

### 4.1 Digital Board Game Infrastructure
- [ ] Research board game implementation approaches
- [ ] Design game state management system
- [ ] Create turn-based game engine
- [ ] Build multiplayer lobby system
- [ ] Implement real-time updates (WebSockets/Pusher)

### 4.2 Game #1: [Your First Board Game]
- [ ] Design digital version of physical game
- [ ] Implement game rules engine
- [ ] Create game board UI
- [ ] Add player interaction components
- [ ] Build AI opponent (if applicable)
- [ ] Add tutorial/help system

### 4.3 Print & Play Downloads
- [ ] Create downloadable PDF generator
- [ ] Design printable game components
- [ ] Add print-friendly rulebooks
- [ ] Implement version control for game files

### 4.4 Playtesting & Feedback
- [ ] Build feedback submission system
- [ ] Create playtester dashboard
- [ ] Implement game analytics
- [ ] Version history tracking

**Branch**: `feature/board-games`
**Estimated Time**: 6-12 months (ongoing)
**Priority**: Medium

---

## Phase 5: Video Game World Building (Ongoing)

### 5.1 Wiki System
- [ ] Research wiki platforms (MediaWiki, Wiki.js, custom)
- [ ] Set up wiki subdomain or section
- [ ] Create article templates (characters, locations, lore)
- [ ] Implement search functionality
- [ ] Add categorization and tagging

### 5.2 Content Management
- [ ] Build content editor (non-programmer friendly)
- [ ] Version control for lore documents
- [ ] Collaborative editing tools
- [ ] Review/approval workflow

### 5.3 Asset Gallery
- [ ] Image gallery for concept art
- [ ] Map viewer/browser
- [ ] Character database
- [ ] Item/equipment catalog

### 5.4 Novella/Story Platform
- [ ] Story chapter viewer
- [ ] Timeline visualization
- [ ] Character relationship graphs
- [ ] Interactive maps

**Branch**: `feature/world-building`
**Estimated Time**: Ongoing
**Priority**: Low-Medium (can start anytime)

---

## Phase 6: Monetization & Business (Future)

### 6.1 E-commerce Integration
- [ ] Stripe/payment gateway integration
- [ ] Shopping cart system
- [ ] Digital downloads platform
- [ ] Physical board game pre-orders

### 6.2 Premium Features
- [ ] Subscription system
- [ ] Exclusive content for supporters
- [ ] Ad-free experience
- [ ] Early access to new games

### 6.3 Board Game Shop Preparation
- [ ] Inventory management
- [ ] Shipping calculator
- [ ] International shipping support
- [ ] Order fulfillment system

**Branch**: `feature/monetization`
**Estimated Time**: TBD
**Priority**: Low (far future)

---

## Infrastructure & DevOps (Ongoing)

### Continuous Improvements
- [ ] Set up CI/CD pipelines
- [ ] Implement automated testing
- [ ] Add performance monitoring (Sentry, New Relic)
- [ ] Database backups automation
- [ ] Security audits
- [ ] Load testing
- [ ] CDN for static assets

### Scaling Considerations
- [ ] Database optimization
- [ ] Caching strategy (Redis)
- [ ] Queue system for background jobs
- [ ] Horizontal scaling preparation

---

## Content Creation (Ongoing)

### Regular Updates
- [ ] Blog/devlog system
- [ ] Development progress updates
- [ ] Game design articles
- [ ] Community engagement

---

## Milestones

### Milestone 1: MVP Homepage (Current) ✅
- Working Laravel site
- Deployed to Railway
- Basic branding

### Milestone 2: Foundation Complete (Target: 2 weeks)
- TALL stack integrated
- Proper content and structure
- Component library started
- Professional homepage

### Milestone 3: First Game Live (Target: 2-3 months)
- At least one browser game playable
- Leaderboard system
- Basic user tracking

### Milestone 4: F1 Season Ready (Target: 4-6 months)
- Full F1 predictions system
- User accounts working
- Automated data fetching

### Milestone 5: Board Game Alpha (Target: 12 months)
- First board game playable online
- Feedback system working
- Print & play available

### Milestone 6: Full Platform (Target: 18-24 months)
- Multiple games live
- Active F1 community
- Board game store operational
- World-building wiki launched

---

## Decision Points

### Technology Choices to Make
1. **F1 API**: Ergast (free) vs FastF1 (Python) vs manual entry?
2. **Wiki Platform**: Custom Laravel vs Wiki.js vs MediaWiki?
3. **Real-time**: Pusher vs Laravel Echo with Redis vs WebSockets?
4. **Board Game Engine**: Custom vs existing framework (Tabletop Simulator API)?
5. **Payments**: Stripe vs PayPal vs both?

### Design Decisions
1. **Dark/Light Mode**: Offer theme toggle?
2. **Mobile First**: Optimize for mobile or desktop priority?
3. **Authentication**: Required or guest-friendly approach?
4. **Monetization**: Free with ads, freemium, or all free initially?

---

## Success Metrics

### Phase 1
- Homepage load time < 2s
- Mobile responsive
- Zero console errors

### Phase 2
- 100+ unique players
- 1000+ games played
- < 1% error rate

### Phase 3
- 50+ active F1 predictors
- 95% prediction submission rate
- Accurate points calculation

### Phase 4
- 10+ board game playtesters
- Positive feedback score > 4/5
- At least 1 game ready for production

---

## Resources Needed

### Immediate
- Laravel expertise (you have this!)
- Design/UI skills (Tailwind will help)
- Time for development

### Near Future
- F1 data source
- Beta testers
- Potential collaborators for world-building

### Long Term
- Payment processing account
- Business registration (for shop)
- Physical location in New Zealand (far future!)

---

**This roadmap is a living document. Update priorities and timelines as you progress!**

**Next Action**: Start Phase 1.1 - Replace lorem ipsum with real content

