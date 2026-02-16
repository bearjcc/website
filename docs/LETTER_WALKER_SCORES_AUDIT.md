# Letter Walker high scores – storage and retrieval audit

## Summary

Scores were stored correctly; they disappeared from "Today's Top Scores" because **"today" was evaluated in UTC** (app default). After UTC midnight, scores with `date_played` from the previous UTC day no longer matched, so the leaderboard showed empty. Commit 7dc486ab did not change score logic (JS/CSS only); the rollover was due to UTC midnight.

## Flow

| Step | Where | What happens |
|------|--------|----------------|
| Submit | `LetterWalkerScoreController::store()` | `date_played` set from `now()->toDateString()` (previously app timezone = UTC). |
| Store | `letter_walker_scores` | Column `date_played` (date), plus `created_at` (timestamp). |
| Daily API | `GET /api/letter-walker/scores/daily` | `LetterWalkerScoreController::daily()` uses `LetterWalkerScore::todaysPuzzle()`. |
| Scope | `LetterWalkerScore::scopeTodaysPuzzle()` | `where('date_played', now()->toDateString())` (previously UTC). |
| UI | `script.js` → `refreshLeaderboard()` | Fetches `/api/letter-walker/scores/daily`, renders "Today's Top Scores". |

## Root cause

- `config/app.php` has `'timezone' => 'UTC'`.
- So `now()->toDateString()` was UTC. Example: scores submitted at 23:38 UTC on 2026-02-15 get `date_played = 2026-02-15`. Once the server passes 00:00:00 UTC on 2026-02-16, "today" becomes 2026-02-16 and those rows are excluded.
- For NZ (e.g. UTC+13), lunch in NZ can be late evening UTC; scores then drop off at UTC midnight, which feels like "scores cleared at a random time" from a NZ perspective.

## Fix (implemented)

- **Leaderboard timezone** is now explicit and configurable:
  - New `config/letter_walker.php` with `leaderboard_timezone` (default `Pacific/Auckland`).
  - Override via `LETTER_WALKER_LEADERBOARD_TIMEZONE` in .env if needed.
- **Store**: `date_played` is set with `now($tz)->toDateString()` using that timezone.
- **Retrieval**: `scopeTodaysPuzzle()` and the `daily()` response `date` use the same timezone. The scope uses `whereDate('date_played', ...)` so the comparison works consistently (e.g. when the column is stored as datetime in SQLite tests).
- "Today" for the leaderboard now rolls over at **midnight NZ time**, not UTC/Singapore.

## Files touched

- `config/letter_walker.php` (new)
- `app/Models/LetterWalkerScore.php` – `scopeTodaysPuzzle()` uses leaderboard timezone
- `app/Http/Controllers/LetterWalkerScoreController.php` – `store()` and `daily()` use leaderboard timezone
- `tests/Unit/LetterWalkerScoreScopeTest.php` – test that scope uses configured timezone

## Optional

- To align with Singapore or another region, set `LETTER_WALKER_LEADERBOARD_TIMEZONE=Asia/Singapore` (or desired zone) in .env on the server.
