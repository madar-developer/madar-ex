<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salla_order_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->string('direction', 20)->default('outbound');
            $table->string('action')->nullable();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->boolean('success')->default(false);
            $table->string('salla_status')->nullable()->index();
            $table->string('shipment_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('message')->nullable();
            $table->longText('payload')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salla_order_logs');
    }
};
