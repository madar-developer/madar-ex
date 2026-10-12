<?php

namespace Tests\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreatesWhatsAppSchema
{
    protected function createWhatsAppSchema(): void
    {
        Schema::dropIfExists('whatsapp_messages');
        Schema::dropIfExists('whatsapp_conversations');
        Schema::dropIfExists('whatsapp_webhook_events');

        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->string('wa_message_id')->nullable()->unique();
            $table->string('phone', 20)->index();
            $table->string('direction', 20)->default('outbound');
            $table->string('type', 30);
            $table->string('template_name')->nullable();
            $table->string('language', 20)->nullable();
            $table->string('status', 30)->default('queued')->index();
            $table->string('error_code')->nullable();
            $table->string('error_message', 500)->nullable();
            $table->string('idempotency_key')->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('whatsapp_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->unique();
            $table->timestamp('last_inbound_at')->nullable()->index();
            $table->string('last_inbound_wa_message_id')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_key')->unique();
            $table->string('event_type', 50)->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }
}
