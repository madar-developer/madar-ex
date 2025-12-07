<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('plate_num')->nullable();
            $table->date('license_expiration_date')->nullable();
            $table->date('insurance_expiration_date')->nullable();
            $table->string('type')->nullable();
            $table->integer('kms')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('plate_num');
            $table->dropColumn('license_expiration_date');
            $table->dropColumn('insurance_expiration_date');
            $table->dropColumn('type');
            $table->dropColumn('kms');
            $table->dropColumn('notes');
        });
    }
}
