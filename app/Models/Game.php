<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

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

    public function catalogConfig(): array
    {
        return config('games.catalog.'.$this->slug, []);
    }

    public function getMotifKey(): string
    {
        return $this->catalogValue('motif', $this->fallbackMotifKey());
    }

    public function hasOpponentChoice(): bool
    {
        return (bool) $this->catalogValue('supports_opponent_choice', false);
    }

    public function supportsPlayerSymbolChoice(): bool
    {
        return (bool) $this->catalogValue('supports_player_symbol', false);
    }

    public function livewireComponentName(): ?string
    {
        return $this->catalogValue('livewire_component');
    }

    public function showView(): string
    {
        return $this->catalogValue('show_view', 'livewire.pages.game-show');
    }

    public function playView(): string
    {
        return $this->catalogValue('play_view', 'livewire.pages.game-play');
    }

    public function layoutView(): string
    {
        return $this->catalogValue('layout', 'components.layouts.app');
    }

    public function redirectsPlayToShow(): bool
    {
        return (bool) $this->catalogValue('redirect_play_to_show', false);
    }

    public function usesAstronomicalTheme(): bool
    {
        return $this->catalogValue('theme', 'astronomical') === 'astronomical';
    }

    public function pace(): string
    {
        return $this->catalogValue('pace', match ($this->type) {
            'arcade' => 'lively',
            'word', 'board' => 'steady',
            default => 'quiet',
        });
    }

    public function bestFor(): string
    {
        return $this->catalogValue('best_for', 'A calm browser game session.');
    }

    public function isRelaxingPick(): bool
    {
        return (bool) $this->catalogValue('relaxing_pick', $this->pace() === 'quiet');
    }

    public function paceLabel(): string
    {
        return match ($this->pace()) {
            'quiet' => 'Quiet start',
            'steady' => 'Steady focus',
            'lively' => 'Livelier pace',
            default => 'Easy to begin',
        };
    }

    public function playComponentProps(string $mode = 'computer', string $playerSymbol = 'X'): array
    {
        $props = ['game' => $this];

        if ($this->slug === 'tic-tac-toe') {
            $props['initialGameMode'] = match ($mode) {
                'friend' => 'pvp',
                'solo' => 'ai-easy',
                default => 'ai-medium',
            };
            $props['initialPlayerSymbol'] = $playerSymbol;
        }

        if ($this->slug === 'connect-4') {
            $props['initialMode'] = $mode;
        }

        return $props;
    }

    public static function knownSlugs(): array
    {
        return array_keys(config('games.catalog', []));
    }

    public static function standalonePlayRedirectSlugs(): array
    {
        return collect(config('games.catalog', []))
            ->filter(fn (array $game): bool => (bool) Arr::get($game, 'redirect_play_to_show', false))
            ->keys()
            ->all();
    }

    private function catalogValue(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->catalogConfig(), $key, $default);
    }

    private function fallbackMotifKey(): string
    {
        return match ($this->type) {
            'board' => 'board',
            'puzzle' => 'puzzle',
            'card' => 'cards',
            default => 'sparkles',
        };
    }
}
