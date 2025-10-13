# TODO

Current tasks and roadmap for Ursa Minor Games.

---

## Phase 1: Foundation ✅ COMPLETE

Homepage live with night sky theme, visual-first game cards, component architecture, tests passing, Railway deployment working.

---

## Phase 2: Browser Games 🎯 NEARLY COMPLETE

### Games Implemented and Polished ✅
- [x] Tic-Tac-Toe — Clean, HSL tokens, no emoji
- [x] Connect 4 — Cleaned up, constellation completion
- [x] Sudoku — Reference implementation, night sky motif
- [x] Minesweeper — Clean, HSL tokens
- [x] Snake — Clean, CSS-only styling (no emoji)
- [x] 2048 — Clean, HSL tokens

### Game UI Cleanup ✅ COMPLETE
- [x] Remove all emoji from controls (replaced with Heroicons)
- [x] Extract 1,000+ lines of inline styles to app.css
- [x] Apply night sky motif (constellation completion messages)
- [x] Convert all colors to HSL tokens
- [x] Add proper aria-labels for accessibility
- [x] Standardize controls (all use .control-btn with icons)
- [x] Respect prefers-reduced-motion throughout
- [x] 44px minimum touch targets

### Remaining Tasks

**Missing Games**:
- [ ] Chess (in database, not yet implemented)
- [ ] Checkers (in database, not yet implemented)

**Nice to Have**:
- [ ] Game-specific loading animations (starfield motif)
- [ ] Score tracking system
- [ ] High score leaderboards
- [ ] Save game state to localStorage

### Next Phase Decision

**Option A**: Implement Chess + Checkers to complete Phase 2  
**Option B**: Move to Phase 3 (F1 Predictions) with 6 solid games  
**Option C**: Polish existing 6 games further (animations, scores)

---

## Phase 3: F1 Predictions (Planned, Weeks 11-18)

Extract from `C:\Users\bearj\Herd\formula1predictions` repo:
- [ ] Race schedule system
- [ ] Prediction forms
- [ ] Scoring engine
- [ ] Leaderboards
- [ ] User accounts

---

## Phase 4: Board Games (Planned, Weeks 19-30)

Extract from `C:\Users\bearj\Herd\agency` repo:
- [ ] Card system
- [ ] Deck builder
- [ ] Game engine
- [ ] Multiplayer

---

## Phase 5: Lore Wiki (Planned, Weeks 31-40)

Extract from `C:\Users\bearj\Herd\tavernsandtreasures`:
- [ ] Wiki system
- [ ] Lore content
- [ ] Contributor tools

---

## Documentation ✅ COMPLETE

- [x] Consolidate to 3 core MD files (README, DESIGN_BIBLE, TODO)
- [x] Create technical MDC files for AI guidance
- [x] Remove redundant docs (reduced 18 → 10 files, 3,779 lines cut)

---

## Current Status

**Phase 2 cleanup complete** ✅ All 6 games cleaned up, 1,150 lines of code removed, HSL tokens throughout, no emoji, constellation-style completion messages.

**Next**: Choose direction — implement missing games, or move to Phase 3?

*Last updated: 2025-10-13*
