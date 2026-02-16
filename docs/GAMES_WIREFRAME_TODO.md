# Games: Wireframe to Implementation Todo

Gap analysis and ordered todo list to align the games experience with the wireframes in `docs/mockups/`. Scope: **games only** (no collaborative wiki, no F1 predictions). Letter Walker remains in scope as a game.

**Reference wireframes:**  
`ursa-minor-homepage-layout-ascii.txt`, `ursa-minor-play-layout-ascii.txt`, `ursa-minor-game-page-layout-ascii.txt`, `ursa-minor-game-entry-layout-ascii.txt`, `ursa-minor-game-not-found-layout-ascii.txt`, plus per-game play mockups (e.g. `ursa-minor-tic-tac-toe-layout-ascii.txt`).

---

## Current vs ideal (summary)

| Aspect | Current | Wireframe ideal |
|--------|--------|------------------|
| **Flow** | Home/games index → direct to play (board) | Home/games index → **Game page** (hero + Play) → **Game entry** (opponent + rules + Start) → **Play view** (board) |
| **Game page** | None; card links go to play | Motif + game name + one-line tagline, single [Play], optional Rules + “More games” links |
| **Game entry** | Inline in some games (e.g. Tic-Tac-Toe mode choice when movesCount === 0) | Dedicated step: “Who do you want to play?” (vs Computer / vs Friend / Solo), Rules, [Start game] |
| **Play view** | Per-game; mix of back link, rules, mode, board, actions | Chrome bar (game name + turn/score), instruction line, board, **info bubbles** below board, **action row** (New game, Hint), controls hint |
| **URLs** | `/games`, `/tic-tac-toe` (etc.) = play, `/{slug}` = GamePlay for DB slugs | `/games`, `/{slug}` = game page, `/{slug}/play` = entry then play |
| **404** | “Game Not Available”, link to home | “404 / Game not found”, short copy, [Browse games] → `/games` |
| **Home hero** | Logo, headline, tagline, Play/Browse | Same; tagline max 6 words (already “The sky is the limit.”) |
| **Games grid** | 3–4–6 cols, motif + title on hover, links to **play** | Same density; links to **game page** |
| **Games index** | 1–3 cols, links to play | Visual-first cards like home, 3–6 cols, link to **game page** |

---

## Phase 1: Routing and game page (foundation)

These unblock all later phases.

### 1.1 Add Game Page (landing: hero + Play)

- [x] **1.1.1** Create Livewire page component and view for “Game page”: hero block (motif + game name + `short_description` as tagline), single primary [Play] button, optional Rules (short/expandable), optional “More games” line (small links to other games). Use `Game` model (by slug); max-width 960px; same header/footer as rest of site.
- [x] **1.1.2** Reuse or mirror game-card motif logic (e.g. slug → motif key: tictactoe, connect4, sudoku, chess, checkers, minesweeper, snake, 2048) for the hero motif on game page.
- [x] **1.1.3** Add route for game page. Recommended: `/{game:slug}` for game page (so `/sudoku` = game page). Then play lives at `/{game:slug}/play`. This requires changing current behavior where `/{slug}` and `/tic-tac-toe` etc. go straight to play.
- [x] **1.1.4** Implement routing strategy:
  - Option A: Remove per-game top-level routes (`/tic-tac-toe`, `/sudoku`, …); use single `/{game:slug}` for game page and `/{game:slug}/play` for play. Resolve slug from DB; 404 when game not found or not published.
  - Option B: Keep top-level game URLs but make them game page: e.g. `/tic-tac-toe` = game page, `/tic-tac-toe/play` = play. Ensure `/{game:slug}` (dynamic) is only hit for slugs that are not reserved (e.g. `games`, `about`, `login`, etc.) and map to Game model.
- [x] **1.1.5** Add named route for game page (e.g. `games.show`) and for play (e.g. `games.play` with slug). Update `games.index` and home to link to game page (`games.show`) instead of `games.play`.

### 1.2 Game not found (404)

- [x] **1.2.1** When game slug not found or not published, return 404 and show wireframe-aligned view: “404”, “Game not found”, short copy (link wrong, go back to games), primary CTA [Browse games] linking to `route('games.index')`.
- [x] **1.2.2** Use same layout (header + horizon footer). Ensure 404 status code is set in response.

### 1.3 Legacy redirects

- [x] **1.3.1** Keep `/games/tic-tac-toe` etc. redirecting to top-level URL; after 1.1, redirect to game page URL (e.g. `/tic-tac-toe`) so old links still work.

---

## Phase 2: Game entry (opponent + rules + Start game)

- [x] **2.1** Add “Game entry” step: screen after [Play] and before the board. Content: breadcrumb “Games → [Game name]”, heading “Who do you want to play?”, options (e.g. vs Computer, vs Friend, Solo), short rules (or expandable), [Start game]. Design: max 960px, same chrome.
- [x] **2.2** Decide URL for entry: e.g. `/{slug}/play` shows entry first; clicking [Start game] either (a) navigates to same URL with “playing” state and mounts game component, or (b) navigates to a dedicated “playing” URL. Prefer (a) for fewer route changes and back-button clarity.
- [x] **2.3** For games that support opponent/mode (Tic-Tac-Toe, Connect 4, Chess, Checkers, etc.): move “Choose your challenge” (and symbol where applicable) from inline in play view into the Game entry screen. Pass selected mode/symbol into the play view (query params or session).
- [x] **2.4** For games without opponent choice (2048, Minesweeper, Snake, Sudoku): Game entry can be minimal (optional rules + [Start game]) or skip straight to play from game page; document decision per game type.
- [x] **2.5** Ensure [Play] on game page goes to entry (or directly to play for games that skip entry). [Start game] on entry loads the actual game component (current Livewire game logic).

---

## Phase 3: Play view structure (chrome, bubbles, actions)

Wireframe: **chrome bar** (game name + turn or score) → **main** (~28rem, centered) → one-line **instruction** → **board** → **info bubbles** (below board) → **action row** (New game, Hint) → optional **controls hint**.

- [x] **3.1** Add reusable “game chrome” bar below site header: game name on left; right side = turn indicator (e.g. “X’s turn”) or score line (e.g. “Score 1234  Best 5678”) per game type. No actions in chrome; actions live below board.
- [x] **3.2** Add reusable “play main” wrapper: max-width ~28rem, centered; contains instruction, board, bubbles, actions, hint.
- [x] **3.3** Add “info bubbles” component/pattern: rounded pill/bubble, label + value (e.g. “X (you) 0”, “Ties 0”, “O (CPU) 0” for 2p; “Score 0”, “Best 0” for score games). Place directly below board.
- [x] **3.4** Add “action row” below bubbles: primary [New game], optional [Hint]. Same style as DESIGN_BIBLE (e.g. border 2px solid #333, bg #333, color #fff for primary).
- [x] **3.5** Add optional one-line controls hint below actions (e.g. “Tap a cell to play.” / “Swipe or use arrow keys.”).
- [x] **3.6** Refactor **Tic-Tac-Toe** play view to use chrome bar, instruction (“Get three in a row.”), board, info bubbles (X you, Ties, O CPU), action row (New game, Hint), and back link only from chrome or a minimal “Games” link if needed. Remove inline mode selection from play view (moved to entry in 2.3).
- [ ] **3.7** Refactor **2048** play view: chrome (game name + Score/Best), instruction (“Slide tiles to combine. Reach 2048.”), 4×4 grid, action row (New game), hint (“Swipe or use arrow keys.”). Bubbles can be Score/Best if not in chrome.
- [ ] **3.8** Refactor **Sudoku**, **Minesweeper**, **Snake**, **Connect 4**, **Checkers**, **Chess** to the same play-view pattern (chrome, instruction, board, bubbles, action row, hint where applicable). Document per-game: chrome content, bubble set, whether Hint exists.
- [ ] **3.9** Remove or reduce “back to home” prominence on play view in favor of “Games” in header; keep play view focused on board and actions.

---

## Phase 4: Homepage and games index

- [x] **4.1** Homepage: Ensure game grid links use `route('games.show', $game->slug)` (game page), not `games.play`. Verify hero: logo lockup, tagline (max 6 words), [Play] and [Browse] both going to `/games` is acceptable per wireframe (wireframe says “both go to games”).
- [x] **4.2** Homepage games section: Optional heading “Free Games to Play” or omit; grid 3–6 columns responsive; cards visual-first (motif + title on hover). Already largely in place; confirm and fix any link/route.
- [x] **4.3** Games index (`/games`): Align with homepage style—same game card component, same motif map, 3–6 column grid (change from current 1–3 if needed). Link each card to game page (`games.show`), not play.
- [x] **4.4** Ensure both home and games index only list published games and use consistent ordering (e.g. by title or fixed order).

---

## Phase 5: Layout and polish

- [ ] **5.1** Verify starfield: full-bleed behind content, no interaction. Already in `app` layout via `starfield.js`; confirm it’s present on game page, entry, and play views.
- [ ] **5.2** Verify header: sticky, glass; nav single-word (Home, Games, About). Already in `components.layouts.app`.
- [ ] **5.3** Verify horizon footer on all game-related pages: sunset line, earth silhouette, back-to-top (star icon), copyright only.
- [ ] **5.4** Game page “More games”: optional one-line or pill links to a few other games (e.g. “Tic-Tac-Toe, Connect 4, Minesweeper”); no big “Similar games” block.
- [ ] **5.5** Responsive: game grid 3–6 cols; play main ~28rem; touch targets and readability on small screens.


---

## Phase 7: Tests and docs

- [x] **7.1** Feature tests: Home and games index link to game page; game page shows hero and [Play]; [Play] leads to entry or play; entry [Start game] leads to play view; 404 for unknown/unpublished slug returns 404 and shows “Game not found” with [Browse games].
- [x] **7.2** Update `PROJECT_STRUCTURE.md` or `GAME_DEVELOPMENT_GUIDE.md` with new flow: game page → game entry → play view; which routes to use; how to add a new game (DB, game page, optional entry, play component).
- [ ] **7.3** Update any README or deployment docs that reference game URLs.

---

## Dependency order

1. **Phase 1** (routing + game page + 404) must be done first so all links can point to game page and play has a clear URL.
2. **Phase 2** (game entry) depends on Phase 1; entry is the screen after [Play] on game page.
3. **Phase 3** (play view refactor) can be done in parallel with or after Phase 2; it does not block Phase 4.
4. **Phase 4** (home + games index) depends on Phase 1 (correct routes and links).
5. **Phase 5** (layout/polish) can be done anytime; **Phase 6** (Letter Walker + edge cases) after 1–2; **Phase 7** (tests/docs) after 1–4 at least.

---

## File reference (current)

| Purpose | Current location |
|--------|------------------|
| Layout (header, starfield, footer) | `resources/views/components/layouts/app.blade.php` |
| Home | `app/Livewire/Pages/Home.php`, `resources/views/livewire/pages/home.blade.php` |
| Games index | `app/Livewire/Pages/GamesIndex.php`, `resources/views/livewire/pages/games-index.blade.php` |
| Game play (wrapper + component map) | `app/Livewire/Pages/GamePlay.php`, `resources/views/livewire/pages/game-play.blade.php` |
| Game not found | `resources/views/livewire/pages/game-not-found.blade.php` (included from game-play) |
| Game card (motif + title) | `resources/views/components/ui/game-card.blade.php` |
| Routes | `routes/web.php` |
| Game model | `app/Models/Game.php` (slug, title, short_description, rules_md, etc.) |

---

*Built under the stars | Ursa Minor Games*
