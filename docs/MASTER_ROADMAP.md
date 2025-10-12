# Ursa Minor Games - Master Roadmap

**Vision**: Build a comprehensive gaming platform that establishes Ursa Minor as a credible game development brand before opening a board game café in New Zealand.

**Current Status**: Homepage deployed to Railway (build failing - needs investigation)

**Last Updated**: 2025-10-12

---

## 🎯 Strategic Goals

### Short-Term (3-6 months)
- Establish web presence with working homepage
- Launch 2-3 browser games to build reputation
- Begin F1 predictions community

### Medium-Term (6-12 months)
- Full browser games lobby with 10+ games
- Active F1 predictions community with 50+ users
- Digital board game prototyping system live

### Long-Term (12-24 months)
- World-building wiki for video game project
- Physical board game designs available digitally
- Strong brand recognition in gaming community
- Foundation for future board game café

---

## 📦 Available Resources

### Existing Repositories (C:\Users\bearj\Herd)

#### formula1predictions
**Status**: Nearly complete, ready to integrate
- Laravel 11 + Livewire 3 + Tailwind CSS
- Complete F1 models (drivers, teams, circuits, races, predictions, standings)
- Real-time notifications with WebSocket
- Drag-and-drop prediction interface
- Comprehensive scoring system
- 28+ passing tests

**Integration Effort**: Medium
**Priority**: High (unique feature, existing community from Discord)

#### games
**Status**: Complete platform with 25+ games
- Laravel + Livewire + Liminal design system
- 25+ classic games (Chess, Checkers, Poker, Solitaire, 2048, Tetris, Sudoku, etc.)
- Game scaffolding system with artisan commands
- AI opponents for strategy games
- Score tracking and statistics
- 470+ game assets with centralized management
- Full accessibility support

**Integration Effort**: Medium-High (requires asset migration)
**Priority**: High (core feature for reputation building)

#### tavernsandtreasures
**Status**: Complete world-building system
- Comprehensive lore for Lumaria world
- 8 distinct regions with unique cultures
- Character generation and progression
- Food and crafting systems
- Wiki-style content management
- Reputation and social systems

**Integration Effort**: High (requires wiki system)
**Priority**: Medium (important for video game project)

#### agency
**Status**: Advanced board game prototyping platform
- Laravel 12 + TALL stack
- Database-first card management system
- Import/export for game data
- Multiple game modes (run-based, campaign, competitive)
- Analytics engine for balance testing
- Manufacturing integration tools

**Integration Effort**: Medium-High
**Priority**: Medium (for future board game designs)

---

## 🏗️ Phase Breakdown

## Phase 0: Emergency Fixes (Current Priority)
**Timeline**: Immediate
**Status**: 🚨 CRITICAL

### 0.1 Railway Deployment
- [ ] Investigate and fix failed Railway build
- [ ] Verify all environment variables
- [ ] Test deployment with minimal changes
- [ ] Document deployment issues and solutions
- [ ] Ensure zero-downtime deployments work

### 0.2 Homepage Polish
- [ ] Verify all assets load correctly
- [ ] Test on mobile devices
- [ ] Fix any console errors
- [ ] Optimize performance (< 2s load time)
- [ ] Add proper error handling

**Exit Criteria**: Site live on Railway with 100% uptime

---

## Phase 1: Foundation & Infrastructure (Weeks 1-2)
**Goal**: Professional homepage that explains the vision

### 1.1 Documentation Organization ✅
- [x] Create master roadmap (this document)
- [ ] Organize existing docs into logical structure
- [ ] Create feature extraction guides
- [ ] Document Railway deployment process
- [ ] Create development workflow guide

### 1.2 TALL Stack Setup
- [ ] Install Node.js on development machine
- [ ] Configure Tailwind CSS with Ursa Minor brand colors
- [ ] Install Alpine.js for interactivity
- [ ] Complete Livewire 3 integration
- [ ] Migrate existing CSS to Tailwind gradually
- [ ] Create base component library

### 1.3 Component Architecture
- [ ] Create main layout (`resources/views/layouts/app.blade.php`)
- [ ] Extract header component
- [ ] Extract footer component
- [ ] Create navigation component
- [ ] Create game card component
- [ ] Rename welcome.blade.php to home.blade.php

### 1.4 Content Pages
- [ ] Create About page (explain the vision)
- [ ] Create Contact page
- [ ] Create 404 error page
- [ ] Update navigation to link all pages
- [ ] Add proper meta tags for SEO

**Deliverable**: Professional homepage with proper structure and content

---

## Phase 2: Browser Games Integration (Weeks 3-10)
**Goal**: Working games lobby with 5-10 games

### 2.1 Games Infrastructure Setup
- [ ] Analyze games repository structure
- [ ] Create migration plan for assets (470+ files)
- [ ] Set up @images alias for asset loading
- [ ] Create games database schema
- [ ] Build game lobby/directory page
- [ ] Implement game state management

### 2.2 Game Selection Strategy
**Priority Games (Easy wins)**:
1. **Sudoku** - Pure logic, no assets needed
2. **2048** - Simple mechanics, minimal assets
3. **Tic Tac Toe** - Basic AI, quick implementation
4. **Minesweeper** - Classic, well-understood
5. **Memory** - Card matching, simple assets

**Future Games (More complex)**:
6. Chess - Complex AI
7. Checkers - Board game mechanics
8. Solitaire - Card game systems
9. Poker - Multiplayer considerations
10. Tetris - Real-time gameplay

### 2.3 Game Migration Process
For each game:
- [ ] Extract game logic from games repo
- [ ] Migrate to Ursa Minor design system
- [ ] Update asset paths for @images alias
- [ ] Create Livewire component
- [ ] Test thoroughly
- [ ] Add to game lobby
- [ ] Write tests

### 2.4 Shared Game Features
- [ ] User best scores (optional login)
- [ ] Guest mode (play without account)
- [ ] Game statistics
- [ ] Leaderboards per game
- [ ] Achievement system (future)

### 2.5 Game Lobby Design
- [ ] Create game card components
- [ ] Grid layout for game selection
- [ ] Filter by category (puzzle, card, board, etc.)
- [ ] Search functionality
- [ ] "Coming Soon" placeholders
- [ ] Featured games section

**Deliverable**: 5 working games with lobby

---

## Phase 3: F1 Predictions System (Weeks 11-18)
**Goal**: Migrate and launch F1 predictions for 2026 season

### 3.1 Code Migration
- [ ] Extract F1 models from formula1predictions
- [ ] Migrate database schema
- [ ] Update namespaces to website project
- [ ] Integrate with main app authentication
- [ ] Test all existing features

### 3.2 F1 Data Integration
- [ ] Research F1 API options (Ergast F1 API, FastF1)
- [ ] Set up API integration
- [ ] Create data seeding for 2026 season
- [ ] Automate race schedule updates
- [ ] Store driver/team data

### 3.3 Core F1 Features
- [ ] User registration and authentication
- [ ] Race prediction submission (drag-and-drop)
- [ ] Fastest lap prediction
- [ ] Prediction deadlines (race start time)
- [ ] Points calculation engine
- [ ] Prediction history view

### 3.4 F1 Social Features
- [ ] Season leaderboard
- [ ] Race-by-race leaderboards
- [ ] Friend leagues (private leaderboards)
- [ ] Prediction insights and statistics
- [ ] Email notifications for deadlines

### 3.5 F1 Admin Panel
- [ ] Race result entry interface
- [ ] Points recalculation tools
- [ ] User management
- [ ] Season management
- [ ] Announcement system for rule changes

### 3.6 Migration from Discord
- [ ] Import historical data (2022-2024) from spreadsheets
- [ ] Create user migration process
- [ ] Communicate transition to community
- [ ] Provide onboarding guides
- [ ] Maintain Discord during transition

**Deliverable**: Complete F1 predictions system ready for 2026 season

---

## Phase 4: Board Game Platform (Weeks 19-30)
**Goal**: Digital prototyping system for board game designs

### 4.1 Agency Platform Integration
- [ ] Migrate agency card management system
- [ ] Set up database-first card approach
- [ ] Implement artisan commands for cards
- [ ] Create card import/export system
- [ ] Build card editor UI

### 4.2 Game Engine
- [ ] State management for turn-based games
- [ ] Multiplayer lobby system
- [ ] Real-time updates (Laravel Reverb/Pusher)
- [ ] Game save/load functionality
- [ ] Undo/redo mechanics

### 4.3 First Board Game: [TBD]
- [ ] Choose first board game to digitize
- [ ] Design digital version
- [ ] Implement rules engine
- [ ] Create game board UI
- [ ] Add tutorial/help system
- [ ] AI opponent (if applicable)

### 4.4 Playtesting Features
- [ ] Feedback submission system
- [ ] Analytics tracking (card usage, win rates, etc.)
- [ ] Balance reporting tools
- [ ] Version control for game iterations
- [ ] Playtester dashboard

### 4.5 Print & Play
- [ ] PDF generator for game components
- [ ] Printable rulebooks
- [ ] Card templates for home printing
- [ ] Asset download system
- [ ] Version history for downloads

**Deliverable**: One complete digital board game with playtesting tools

---

## Phase 5: World-Building Wiki (Weeks 31-40)
**Goal**: Collaborative lore system for video game project

### 5.1 Wiki Platform Decision
**Options**:
1. **Custom Laravel Wiki** - Full control, integrated with site
2. **Wiki.js** - Modern, feature-rich, Node.js
3. **MediaWiki** - Industry standard, PHP
4. **DokuWiki** - File-based, simple

**Recommendation**: Custom Laravel wiki for full integration

### 5.2 Wiki Core Features
- [ ] Article creation and editing
- [ ] Markdown support with extensions
- [ ] Category and tagging system
- [ ] Search functionality (Laravel Scout)
- [ ] Version history
- [ ] Revision comparison

### 5.3 World-Building Specific Features
- [ ] Article templates (Characters, Locations, Events, Items)
- [ ] Relationship graphs
- [ ] Timeline visualization
- [ ] Interactive maps
- [ ] Character databases
- [ ] Lore consistency checking

### 5.4 Collaboration Tools
- [ ] Multi-user editing (non-programmer friendly)
- [ ] Review/approval workflow
- [ ] Comment system
- [ ] Task assignments
- [ ] Notification system

### 5.5 Content Migration
- [ ] Import Lumaria content from tavernsandtreasures
- [ ] Import GAME_BIBLE.md content
- [ ] Organize into wiki structure
- [ ] Add cross-references
- [ ] Create navigation

### 5.6 Asset Management
- [ ] Image gallery for concept art
- [ ] Map viewer/uploader
- [ ] Character portrait system
- [ ] Item catalog with images
- [ ] Audio/music library

**Deliverable**: Functioning wiki with Lumaria world content

---

## Phase 6: Enhancement & Polish (Weeks 41-48)
**Goal**: Professional polish and additional features

### 6.1 User Accounts System
- [ ] Laravel Breeze/Jetstream implementation
- [ ] User profiles
- [ ] Avatar system
- [ ] Account settings
- [ ] Privacy controls
- [ ] Data export (GDPR compliance)

### 6.2 Notification System
- [ ] In-app notifications
- [ ] Email notifications
- [ ] Notification preferences
- [ ] Real-time updates (WebSocket)

### 6.3 Social Features
- [ ] Friend system
- [ ] Activity feeds
- [ ] Achievements
- [ ] Badges and rewards
- [ ] Social sharing

### 6.4 Blog/Devlog
- [ ] Article system for updates
- [ ] Category and tags
- [ ] RSS feed
- [ ] Comments (or link to Discord)
- [ ] Featured posts

### 6.5 Performance Optimization
- [ ] Database query optimization
- [ ] Caching strategy (Redis)
- [ ] CDN for assets
- [ ] Image optimization
- [ ] Code splitting

### 6.6 Analytics
- [ ] Google Analytics integration
- [ ] Custom event tracking
- [ ] User behavior analysis
- [ ] A/B testing framework
- [ ] Performance monitoring

**Deliverable**: Polished, feature-complete platform

---

## Phase 7: Monetization & Business (Future)
**Goal**: Sustainable income to support café dream

### 7.1 E-commerce
- [ ] Stripe/PayPal integration
- [ ] Digital downloads (board games)
- [ ] Physical product pre-orders
- [ ] Shipping calculator
- [ ] Order management

### 7.2 Premium Features
- [ ] Subscription system (Paddle/Stripe)
- [ ] Exclusive content for supporters
- [ ] Ad-free experience
- [ ] Early access to new games
- [ ] Cosmetic items

### 7.3 Board Game Shop Prep
- [ ] Inventory management
- [ ] Print-on-demand integration
- [ ] International shipping
- [ ] Order fulfillment
- [ ] Customer support system

**Deliverable**: Revenue-generating platform

---

## 🛠️ Technical Infrastructure

### Continuous Improvements
- [ ] CI/CD pipeline (GitHub Actions)
- [ ] Automated testing on PR
- [ ] Database backups (daily)
- [ ] Error monitoring (Sentry)
- [ ] Performance monitoring
- [ ] Security audits (quarterly)
- [ ] Load testing

### Scaling Considerations
- [ ] Database optimization
- [ ] Redis caching
- [ ] Queue system for background jobs
- [ ] Horizontal scaling preparation
- [ ] CDN integration

### Documentation Standards
- [ ] Keep all .md files in docs/
- [ ] Update roadmap weekly
- [ ] Document all major decisions
- [ ] Maintain API documentation
- [ ] Create user guides

---

## 📊 Success Metrics

### Phase 1 (Foundation)
- Homepage loads in < 2s
- Zero console errors
- Mobile responsive
- 100% Railway uptime

### Phase 2 (Browser Games)
- 100+ unique players/month
- 1000+ games played/month
- < 1% error rate
- 4+ star average rating

### Phase 3 (F1 Predictions)
- 50+ active predictors by season start
- 90%+ prediction submission rate
- Discord community migrated
- Zero scoring errors

### Phase 4 (Board Games)
- 10+ active playtesters
- 50+ playtesting sessions
- Positive feedback (> 4/5)
- 1 game ready for physical production

### Phase 5 (Wiki)
- 100+ wiki articles
- 5+ active contributors
- Complete Lumaria documentation
- Search < 100ms response time

### Phase 6 (Enhancement)
- 500+ registered users
- 50%+ user retention (monthly)
- < 100ms average page load
- 99.9% uptime

### Phase 7 (Monetization)
- Sustainable income (> hosting costs)
- 10+ premium subscribers
- Positive ROI on development time

---

## 🎯 Milestones

### Milestone 1: Site Live ✅
- Laravel homepage deployed
- Railway auto-deploy working
- Basic branding complete

### Milestone 2: Foundation Complete (Target: Week 2)
- TALL stack integrated
- Professional homepage
- Component library started
- Documentation organized

### Milestone 3: First Games Live (Target: Week 10)
- 5 browser games playable
- Game lobby functional
- Basic user tracking
- Zero critical bugs

### Milestone 4: F1 Ready (Target: Week 18)
- Complete F1 system migrated
- Community transitioned from Discord
- Ready for 2026 season
- Automated data fetching

### Milestone 5: Board Game Alpha (Target: Week 30)
- First board game playable
- Playtesting tools working
- Feedback system active
- Print & play available

### Milestone 6: Wiki Launch (Target: Week 40)
- Wiki platform live
- Lumaria content migrated
- Collaboration tools working
- Non-programmers can contribute

### Milestone 7: Full Platform (Target: Week 48)
- All features integrated
- User accounts working
- Premium features available
- Sustainable income started

### Milestone 8: Board Game Café Prep (Target: 18-24 months)
- Physical board games designed
- Strong online community
- Brand recognition established
- Revenue supporting café plans

---

## 🚨 Risk Management

### Technical Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Railway build failures | HIGH | Keep Railway compatibility, test frequently |
| Asset migration issues | MEDIUM | Test asset loading early, use @images alias |
| Database performance | MEDIUM | Optimize queries, use indexes, implement caching |
| Real-time features overhead | LOW | Use Laravel Reverb, load test early |

### Project Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Scope creep | HIGH | Stick to roadmap, resist feature additions |
| Burnout | HIGH | Work on one feature at a time, celebrate wins |
| Technical debt | MEDIUM | Maintain code quality, refactor regularly |
| Community growth | MEDIUM | Marketing strategy, social media presence |

### Business Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| No users | HIGH | Build community during development, Discord/Reddit |
| Hosting costs | MEDIUM | Monitor usage, optimize before scaling |
| Legal issues | LOW | Proper licensing, terms of service, privacy policy |
| Competition | LOW | Unique combination of features, personal touch |

---

## 📋 Decision Log

### Technology Decisions

**TALL Stack (Tailwind, Alpine, Laravel, Livewire)**
- **Decision**: Use TALL stack for all features
- **Reason**: Consistent with existing repos, Laravel-native
- **Date**: 2025-10-12

**Railway Hosting**
- **Decision**: Continue using Railway for hosting
- **Reason**: Already set up, auto-deploy from GitHub
- **Date**: 2025-10-12

**F1 API Choice**
- **Decision**: TBD - Ergast vs FastF1 vs manual entry
- **Options**: 
  - Ergast (free, REST API, good coverage)
  - FastF1 (Python, detailed telemetry, requires Docker)
  - Manual entry (most control, most work)
- **Timeline**: Decide by Phase 3

**Wiki Platform**
- **Decision**: Custom Laravel wiki
- **Reason**: Full integration, consistent UX, custom features
- **Date**: 2025-10-12

### Design Decisions

**Night Sky Theme**
- **Decision**: Maintain throughout all features
- **Reason**: Strong brand identity, consistent UX
- **Date**: 2025-10-12

**Guest-Friendly Approach**
- **Decision**: Allow playing games without account
- **Reason**: Lower barrier to entry, better user acquisition
- **Date**: 2025-10-12

**Mobile-First Design**
- **Decision**: Optimize for mobile from the start
- **Reason**: Most users will be on mobile devices
- **Date**: 2025-10-12

---

## 🔄 Weekly Workflow

### Monday
- Review progress from previous week
- Update roadmap and todo list
- Plan week's tasks
- Prioritize blockers

### Tuesday-Friday
- Focus on current phase tasks
- One todo item at a time
- Commit frequently with conventional commits
- Test before committing

### Saturday
- Code review and refactoring
- Update documentation
- Test on mobile devices
- Deploy to Railway if ready

### Sunday
- Community engagement (Discord, Reddit)
- Content creation (blog posts, devlogs)
- Long-term planning
- Rest and recharge

---

## 📚 Reference Documents

### Internal Documentation
- [Brand Guidelines](../BRAND_GUIDELINES.md) - Visual identity and design system
- [Project Structure](../PROJECT_STRUCTURE.md) - File organization
- [Contributing Guide](../CONTRIBUTING.md) - Development workflow
- [Deployment Guide](../DEPLOYMENT.md) - Railway deployment
- [Phase 1 Progress](../PHASE1_PROGRESS.md) - Current progress tracking

### External Resources
- [Laravel Docs](https://laravel.com/docs)
- [Livewire Docs](https://laravel-livewire.com/docs)
- [Tailwind Docs](https://tailwindcss.com/docs)
- [Railway Docs](https://docs.railway.app)

### Repository Documentation
- `C:\Users\bearj\Herd\formula1predictions\README.md` - F1 system docs
- `C:\Users\bearj\Herd\games\README.md` - Games platform docs
- `C:\Users\bearj\Herd\tavernsandtreasures\GAME_BIBLE.md` - World-building lore
- `C:\Users\bearj\Herd\agency\README.md` - Board game platform docs

---

## 🎯 Next Actions

### Immediate (Today)
1. Fix Railway deployment issue
2. Verify site is live and working
3. Organize documentation structure
4. Create initial todo list

### This Week
1. Complete Phase 0 (deployment fix)
2. Start Phase 1.2 (TALL stack setup)
3. Create component library
4. Polish homepage content

### This Month
1. Complete Phase 1 (Foundation)
2. Start Phase 2 (Browser Games)
3. Migrate first 2-3 games
4. Launch games lobby

---

**Remember**: "Eat the elephant one bite at a time." Focus on one todo item, complete it thoroughly, then move to the next. Each small win builds momentum toward the big vision.

**Vision Statement**: By the time we open our board game café in New Zealand, Ursa Minor Games will be a recognized name in the gaming community, with a loyal following built through years of creating and sharing amazing gaming experiences.

---

*This roadmap is a living document. Update it as priorities shift, decisions are made, and progress is achieved. Review weekly, revise monthly, celebrate milestones.*

