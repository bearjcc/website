# TODO

Current tasks and roadmap for Ursa Minor Games.

---

## Phase 1: Foundation ✅ COMPLETE

Homepage live with night sky theme, visual-first game cards, component architecture, tests passing, Railway deployment working.

---

## Phase 2: Browser Games 🎯 IN PROGRESS

### Games Already Implemented ✅
- [x] Tic-Tac-Toe
- [x] Connect 4
- [x] Sudoku
- [x] Minesweeper
- [x] Snake
- [x] 2048

### Cleanup Needed

**Game UI Polish** (align with DESIGN_BIBLE):
- [ ] Remove emoji from game controls (Sudoku: 🔄, 💡, 🗑️, ✏️)
- [ ] Extract inline styles to app.css
- [ ] Apply night sky motif to game UI (loading, completion states)
- [ ] Ensure all games use HSL color tokens (not hardcoded hex)
- [ ] Add constellation-style progress indicators
- [ ] Standardize game controls across all games

**Missing Games**:
- [ ] Chess (in database, not implemented)
- [ ] Checkers (in database, not implemented)

### Next Immediate Steps

1. **Clean up Sudoku** (reference game):
   - Replace emoji with Heroicons
   - Move inline styles to CSS
   - Apply starfield motif to loading/completion
   - Use HSL tokens throughout

2. **Apply same cleanup to other 5 games**

3. **Implement Chess and Checkers**

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

## Current Focus

**Clean up existing games to match DESIGN_BIBLE principles** — remove emoji, apply night sky motif, use color tokens consistently.

*Last updated: 2025-10-13*
