<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    /**
     * Resolve route binding so only published games are returned; otherwise 404.
     */
    public function resolveRouteBinding($value, $field = null): Model
    {
        return static::query()
            ->where($field ?? 'slug', $value)
            ->published()
            ->firstOrFail();
    }

    protected $fillable = [
        'slug',
        'title',
        'type',
        'status',
        'short_description',
        'rules_md',
        'options_json',
    ];

    protected $casts = [
        'options_json' => 'array',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Canonical slug (and optional type) to motif key for game-card and game page hero.
     * Keys: tictactoe, connect4, sudoku, chess, checkers, minesweeper, snake, 2048, board, puzzle, cards, sparkles.
     */
    public static function motifKeyForSlug(string $slug, ?string $type = null): string
    {
        $bySlug = match ($slug) {
            'tic-tac-toe' => 'tictactoe',
            'connect-4' => 'connect4',
            'twenty-forty-eight', '2048' => '2048',
            'sudoku' => 'sudoku',
            'minesweeper' => 'minesweeper',
            'snake' => 'snake',
            'checkers' => 'checkers',
            'chess' => 'chess',
            'letter-walker' => 'board',
            default => null,
        };
        if ($bySlug !== null) {
            return $bySlug;
        }
        if ($type !== null) {
            $byType = match ($type) {
                'board' => 'board',
                'puzzle' => 'puzzle',
                'card' => 'cards',
                default => null,
            };
            if ($byType !== null) {
                return $byType;
            }
        }
        return 'sparkles';
    }

    public function getMotifKey(): string
    {
        return self::motifKeyForSlug($this->slug, $this->type);
    }

    /** Slugs that show full game entry (opponent + rules + Start). Others get minimal entry (rules + Start). */
    public static function slugsWithOpponentChoice(): array
    {
        return ['tic-tac-toe', 'connect-4', 'chess', 'checkers'];
    }

    public function hasOpponentChoice(): bool
    {
        return in_array($this->slug, self::slugsWithOpponentChoice(), true);
    }
}
