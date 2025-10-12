<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

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
}
