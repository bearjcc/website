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
}
