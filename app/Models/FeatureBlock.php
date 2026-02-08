<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureBlock extends Model
{
    protected $fillable = [
        'kind',
        'ref_id',
        'order',
    ];

    public function getReference()
    {
        return match ($this->kind) {
            'game' => Game::find($this->ref_id),
            'post' => Post::find($this->ref_id),
            default => null,
        };
    }
}
