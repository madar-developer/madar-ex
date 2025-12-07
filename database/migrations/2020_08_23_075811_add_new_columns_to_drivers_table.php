<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->date('identity_expiration_date')->nullable();
            $table->date('car_receive_date')->nullable();
            $table->enum('type', ['internal', 'external'])->default('internal');
            $table->integer('commission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('identity_expiration_date');
            $table->dropColumn('car_receive_date');
            $table->dropColumn('type');
            $table->dropColumn('commission');
        });
    }
}
