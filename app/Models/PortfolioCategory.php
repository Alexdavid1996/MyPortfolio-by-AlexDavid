<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'category_id');
    }
}
