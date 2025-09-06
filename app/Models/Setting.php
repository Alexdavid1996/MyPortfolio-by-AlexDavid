<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'favicon',
        'theme',
        'social_links',
        'contact_email',
        'footer_copyright',
        'home_page_h1',
        'home_page_description',
        'default_share_image',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
}
