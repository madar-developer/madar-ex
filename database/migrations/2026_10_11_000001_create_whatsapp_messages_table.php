<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
