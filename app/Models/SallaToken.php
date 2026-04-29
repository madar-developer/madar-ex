<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SallaToken extends Model
{
    protected $table = 'salla_tokens';

    protected $fillable = [
        'merchant_id',
        'company_id',
        'access_token',
        'refresh_token',
        'access_token_expires_at',
        'refresh_token_expires_at',
    ];

    protected $casts = [
        'access_token_expires_at' => 'datetime',
        'refresh_token_expires_at' => 'datetime',
    ];
}
