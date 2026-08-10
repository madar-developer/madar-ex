<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceGeofencesTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_geofences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->unsignedInteger('radius_meters')->default(100);
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_geofences');
    }
}
