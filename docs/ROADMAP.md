# Roadmap (high level)

This file is intentionally short. Detailed planning lives in your usual tracker or discussions.

| Area | Direction |
|------|------------|
| This Laravel app | Marketing site, about, auth, contributor lore, Sudoku and Letter Walker APIs, redirects for legacy game URLs. |
| Small games (browser) | Served under the configured games base URL with **path URLs** on the apex (e.g. `https://ursaminor.games/sudoku`). Set `GAMES_BASE_URL` in production. |
| Taverns and Treasures | Large game; its own app and host (`https://taverns.ursaminor.games`), not this repo. |
| Sister products | e.g. F1 predictions, linked from the home grid; each has its own URL. See [../config/ursa_sites.php](../config/ursa_sites.php) for keys used in the UI. |

For implementation detail, [GAME_DEVELOPMENT_GUIDE.md](GAME_DEVELOPMENT_GUIDE.md) and [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md).
