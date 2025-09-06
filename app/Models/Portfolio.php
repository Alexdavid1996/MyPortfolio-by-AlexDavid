<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'short_description',
        'description',
        'tech_stack',
        'thumbnail_url',
        'gallery_urls',
        'featured',
        'status',
        'published_at',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'gallery_urls' => 'array',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(PortfolioCategory::class, 'category_id');
    }
}
