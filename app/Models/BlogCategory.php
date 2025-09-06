<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'meta_title',
        'meta_description',
        'canonical_url',
    ];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
