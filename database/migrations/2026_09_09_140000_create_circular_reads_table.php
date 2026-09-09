<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circular_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('circular_id');
            $table->unsignedBigInteger('driver_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(['circular_id', 'driver_id']);
            $table->foreign('circular_id')->references('id')->on('circulars')->onDelete('cascade');
            $table->index('driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circular_reads');
    }
};
