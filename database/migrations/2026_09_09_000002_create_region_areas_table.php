<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('region_areas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->json('coordinates');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('region_areas');
    }
};
