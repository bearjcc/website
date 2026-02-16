<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\LetterWalkerScore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LetterWalkerScoreScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_todays_puzzle_scope_returns_only_today_records(): void
    {
        LetterWalkerScore::factory()->count(2)->create();

        $query = LetterWalkerScore::todaysPuzzle()->get();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $query);
    }

    public function test_todays_puzzle_uses_configured_leaderboard_timezone(): void
    {
        $this->app->make('config')->set('letter_walker.leaderboard_timezone', 'Pacific/Auckland');
        $tz = 'Pacific/Auckland';
        $todayInTz = now($tz)->toDateString();

        LetterWalkerScore::factory()->create(['date_played' => $todayInTz, 'score' => 100]);
        LetterWalkerScore::factory()->create(['date_played' => now($tz)->subDay()->toDateString(), 'score' => 90]);

        $todays = LetterWalkerScore::todaysPuzzle()->orderBy('score', 'desc')->get();

        $this->assertCount(1, $todays);
        $this->assertSame(100, $todays->first()->score);
    }
}
