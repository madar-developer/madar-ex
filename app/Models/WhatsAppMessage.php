<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'wa_message_id',
        'phone',
        'direction',
        'type',
        'template_name',
        'language',
        'status',
        'error_code',
        'error_message',
        'idempotency_key',
    ];

    protected $hidden = [
        'phone',
    ];
}
