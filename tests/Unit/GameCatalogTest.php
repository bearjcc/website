<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Game;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GameCatalogTest extends TestCase
{
    #[Test]
    public function games_use_the_astronomical_theme_by_default(): void
    {
        $game = new Game([
            'slug' => 'sudoku',
            'type' => 'puzzle',
        ]);

        $this->assertTrue($game->usesAstronomicalTheme());
        $this->assertSame('livewire.pages.game-show', $game->showView());
        $this->assertSame('livewire.pages.game-play', $game->playView());
    }

    #[Test]
    public function standalone_games_can_override_the_default_theme(): void
    {
        $game = new Game([
            'slug' => 'letter-walker',
            'type' => 'word',
        ]);

        $this->assertFalse($game->usesAstronomicalTheme());
        $this->assertSame('games.letter-walker', $game->showView());
        $this->assertSame('games.letter-walker', $game->playView());
        $this->assertSame('layouts.blank', $game->layoutView());
        $this->assertTrue($game->redirectsPlayToShow());
    }
}
