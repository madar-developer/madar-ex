<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistanceToCarMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_maintenances', function (Blueprint $table) {
            $table->string('distance')->nullable();
            $table->string('oil')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_maintenances', function (Blueprint $table) {
            $table->dropColumn('distance');
            $table->dropColumn('oil');
        });
    }
}
