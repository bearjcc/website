<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LorePage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'status',
        'body_md',
        'excerpt_md',
        'author_id',
        'tags_json',
    ];

    protected $casts = [
        'tags_json' => 'array',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
