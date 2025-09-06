<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleSearch extends Model
{
    use HasFactory;

    protected $table = 'google_search';

    protected $fillable = [
        'verification_code',
    ];
}
