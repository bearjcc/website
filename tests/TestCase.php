<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    // region Small games host (path on ursaminor.games)

    /**
     * apex domain for small games, e.g. `https://ursaminor.games/sudoku` (not games. or per-game subdomains).
     * Must match `config('services.games.base_url')` / `GAMES_BASE_URL`.
     */
    protected function smallGamesApexBaseUrl(): string
    {
        return rtrim((string) config('services.games.base_url', 'https://ursaminor.games'), '/');
    }

    /**
     * @param  string  $path  slug or slug/play (no leading slash)
     */
    protected function smallGamesApexUrl(string $path = ''): string
    {
        $base = $this->smallGamesApexBaseUrl();

        return $path === ''
            ? $base.'/'
            : $base.'/'.ltrim($path, '/');
    }

    /**
     * Assert 301 to the small-games host (see routes/web.php $gamesRedirectUrl).
     */
    protected function assertRedirectsToSmallGamesApex(TestResponse $response, string $path = ''): void
    {
        $response->assertStatus(301);
        $response->assertRedirect($this->smallGamesApexUrl($path));
    }

    // endregion
}
