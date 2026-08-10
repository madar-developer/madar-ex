<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('geofence_id')->nullable();
            $table->enum('type', ['check_in', 'check_out']);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->float('distance_meters')->nullable();
            $table->boolean('within_geofence')->default(false);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers')->cascadeOnDelete();
            $table->foreign('geofence_id')->references('id')->on('attendance_geofences')->nullOnDelete();
            $table->index(['driver_id', 'type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
}
