# Repository Audit — February 2025

Findings from project audit. Use this to prioritize fixes.

## Documentation Gaps

| Reference | Location | Status |
|-----------|----------|--------|
| `docs/BRAND_GUIDELINES.md` | PROJECT_STRUCTURE, docs/README | Added stub pointing to DESIGN_BIBLE.md. |
| `docs/ROADMAP.md` | CONTRIBUTING.md | Exists. |
| Design reference | PROJECT_STRUCTURE | Use `DESIGN_BIBLE.md` (root). |
| `.cursor/rules/*.mdc` | AGENTS.md, docs/README | Optional; directory may not exist. |

## URL Inconsistency

Canonical per AGENTS: `http://website.test/` (Laravel Herd project name). Standardized in PROJECT_STATUS, DESIGN_BIBLE, CONTRIBUTING, DEPLOYMENT_GUIDE.

## Test Failures

- **Connect4EngineTest**: Passing (21 tests).
- **HumanSolverTest**: `it_finds_locked_candidates` — fixed; now asserts LockedCandidates with eliminations.

## Deprecation Warnings

~~PHPUnit doc-comment metadata deprecated for PHPUnit 12.~~ Migrated to `#[Test]` attributes in GameFunctionalityTest, HumanSolverTest, SudokuBoardTest, SudokuGeneratorTest, SudokuSolverTest, SudokuGameTest.

## Git / File State

- **Modified**: `letter-walker` (script.js, styles.css, blade)
- **Backup files**: `*.bak` in .gitignore; no .bak files in letter-walker.
- **Untracked**: `docs/2048/` (upstream 2048 source), `screenshots/` (both in .gitignore as needed).
- **Tracked docs**: `docs/AUDIT.md`, `docs/BRAND_GUIDELINES.md`, `docs/ROADMAP.md`.

## Games Inventory

| Game | Status | Notes |
|------|--------|-------|
| Tic-Tac-Toe | Livewire | AI opponents |
| Connect 4 | Livewire | Engine tests passing |
| Sudoku | Livewire | Reference implementation |
| 2048 | Livewire | |
| Minesweeper | Livewire | |
| Snake | Livewire | |
| Chess | Livewire | Engine, tests |
| Checkers | Livewire | Engine, tests |
| Letter Walker | View + vanilla JS | Wordle-style, score API |

## Recommended Actions

1. ~~Fix doc references (BRAND_GUIDELINES, ROADMAP)~~ — BRAND_GUIDELINES stub added; ROADMAP exists
2. ~~Fix Connect4 engine / tests~~ — passing
3. ~~Remove or ignore .bak files~~ — in .gitignore
4. ~~Standardize local URL in all docs to website.test~~ — done
5. Letter Walker already in TODO (Phase 2 games list)
6. ~~Add docs/2048 to .gitignore~~ — already in .gitignore
7. ~~Add screenshots to .gitignore~~ — already in .gitignore
