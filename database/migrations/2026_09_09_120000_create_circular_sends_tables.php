<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circular_sends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source', 32)->default('notification');
            $table->boolean('sent_to_all_companies')->default(false);
            $table->boolean('sent_to_all_drivers')->default(false);
            $table->boolean('sent_to_all_admins')->default(false);
            $table->timestamps();

            $table->index('admin_id');
        });

        Schema::create('circular_send_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('circular_send_id');
            $table->string('recipient_type', 32);
            $table->unsignedBigInteger('recipient_id');
            $table->string('recipient_name')->nullable();
            $table->timestamps();

            $table->foreign('circular_send_id')
                ->references('id')
                ->on('circular_sends')
                ->onDelete('cascade');
            $table->index(['recipient_type', 'recipient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circular_send_recipients');
        Schema::dropIfExists('circular_sends');
    }
};
