# Audit notes

Living notes so [docs/README.md](README.md) and [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) can point here without broken links.

## Checks that matter

- **Tests:** `php artisan test` (from repo root, with dependencies installed). Run before merge when you touch behavior.
- **Format:** `./vendor/bin/pint` for PHP in this app.
- **Local browse:** `http://website.test/` (Laravel Herd), not a second PHP server, per [AGENTS.md](../AGENTS.md).

## URL model (2026)

- **Small games** resolve on the **games** origin with **path** segments (e.g. `/sudoku`), not `games.` or per-game subdomains on the apex. Production base: `GAMES_BASE_URL` (see `config/services.php`, `.env.example`).
- **Taverns and Treasures** is a **separate** product on `https://taverns.ursaminor.games`.

Deeper audits belong in the issue tracker or in focused docs when you do a real review pass. Replace this file’s date when you re-audit.
