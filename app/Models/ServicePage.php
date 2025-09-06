<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePage extends Model
{
    protected $table = 'services_page';

    protected $fillable = [
        'title',
        'description',
        'meta_description',
        'feature_image_url',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
