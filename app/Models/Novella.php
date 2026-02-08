<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novella extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'status',
        'synopsis_md',
        'file_path',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
