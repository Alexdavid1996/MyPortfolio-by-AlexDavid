<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomHeaderAndBody extends Model
{
    use HasFactory;

    protected $table = 'custom_header_and_body';

    protected $fillable = [
        'head_code',
        'body_code',
    ];
}
