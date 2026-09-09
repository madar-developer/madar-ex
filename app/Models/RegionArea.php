<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionArea extends Model
{
    protected $fillable = [
        'title',
        'code',
        'coordinates',
        'active',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
