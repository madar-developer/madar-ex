<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlexibleLoginToDriversTable extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            if (!Schema::hasColumn('drivers', 'flexible_attendance')) {
                $table->boolean('flexible_attendance')->default(0)->after('image');
            }
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'flexible_attendance')) {
                $table->dropColumn('flexible_attendance');
            }
        });
    }
}
