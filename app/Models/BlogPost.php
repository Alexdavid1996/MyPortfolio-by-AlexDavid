<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'excerpt',
        'body',
        'status',
        'published_at',
        'cover_image_url',
        'meta_title',
        'meta_description',
        'reading_time_minutes',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
