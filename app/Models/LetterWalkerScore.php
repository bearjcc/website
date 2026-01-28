<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterWalkerScore extends Model
{
    use HasFactory;

    protected $table = 'letter_walker_scores';

    protected $fillable = [
        'user_id',
        'player_name',
        'score',
        'moves',
        'words_found',
        'puzzle_number',
        'date_played',
    ];

    protected $casts = [
        'date_played' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTodaysPuzzle($query)
    {
        return $query->where('date_played', now()->toDateString());
    }
}
