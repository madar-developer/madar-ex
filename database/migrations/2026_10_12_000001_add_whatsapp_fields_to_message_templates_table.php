<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('message_templates', function (Blueprint $table) {
            $table->boolean('whatsapp_enabled')->default(false)->after('active');
            $table->string('whatsapp_template_name')->nullable()->after('whatsapp_enabled');
            $table->string('whatsapp_template_id')->nullable()->after('whatsapp_template_name');
            $table->string('whatsapp_language', 20)->nullable()->default('ar')->after('whatsapp_template_id');
            $table->json('whatsapp_variables')->nullable()->after('whatsapp_language');
        });
    }

    public function down(): void
    {
        Schema::table('message_templates', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_enabled',
                'whatsapp_template_name',
                'whatsapp_template_id',
                'whatsapp_language',
                'whatsapp_variables',
            ]);
        });
    }
};
