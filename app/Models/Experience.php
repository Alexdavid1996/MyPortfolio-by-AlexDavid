<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'company_name',
        'role_title',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'summary',
        'responsibilities',
        'logo_url',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'responsibilities' => 'array',
    ];
}
