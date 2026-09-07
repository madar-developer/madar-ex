<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->text('body');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('message_template_company', function (Blueprint $table) {
            $table->unsignedBigInteger('message_template_id');
            $table->unsignedBigInteger('company_id');

            $table->primary(['message_template_id', 'company_id']);
            $table->foreign('message_template_id')
                ->references('id')
                ->on('message_templates')
                ->onDelete('cascade');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_template_company');
        Schema::dropIfExists('message_templates');
    }
};
