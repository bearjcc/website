<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Game;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Ursa Minor - Small games. Big craft.')]
class Home extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        $games = Game::published()->orderBy('title')->get();
        $relaxingGames = $games
            ->filter(fn (Game $game): bool => $game->isRelaxingPick())
            ->take(3)
            ->values();

        $featuredGame = $relaxingGames->first() ?? $games->first();

        $sisterSites = self::sisterSiteCards();

        return view('livewire.pages.home', [
            'games' => $games,
            'relaxingGames' => $relaxingGames,
            'featuredGame' => $featuredGame,
            'sisterSites' => $sisterSites,
        ]);
    }

    /**
     * @return list<array{key: string, href: string, title: string, blurb: string, variant: string}>
     */
    public static function sisterSiteCards(): array
    {
        $useProduction = app()->isProduction();
        $out = [];

        foreach (self::sisterSiteDefinitions() as $site) {
            $url = $useProduction ? (string) $site['production'] : (string) $site['local'];
            $out[] = [
                'key' => (string) $site['key'],
                'href' => $url,
                'title' => __($site['title']),
                'blurb' => __($site['blurb']),
                'variant' => (string) $site['variant'],
            ];
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function sisterSiteDefinitions(): array
    {
        $sites = config('ursa_sites.sister_sites');
        if (is_array($sites) && $sites !== []) {
            return $sites;
        }
        if (! is_file($path = config_path('ursa_sites.php'))) {
            return [];
        }

        $loaded = require $path;
        if (! is_array($loaded) || ! is_array($fromFile = $loaded['sister_sites'] ?? null)) {
            return [];
        }

        return $fromFile;
    }
}
