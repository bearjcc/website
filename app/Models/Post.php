<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'body_md',
        'status',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
