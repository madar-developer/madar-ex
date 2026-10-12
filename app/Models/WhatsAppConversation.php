<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppConversation extends Model
{
    protected $table = 'whatsapp_conversations';

    protected $fillable = [
        'phone',
        'last_inbound_at',
        'last_inbound_wa_message_id',
    ];

    protected $casts = [
        'last_inbound_at' => 'datetime',
    ];

    protected $hidden = [
        'phone',
    ];
}
