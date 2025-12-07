<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddByColumnToFianancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fianances', function (Blueprint $table) {
            $table->unsignedBigInteger('added_by_id')->nullable();
            $table->foreign('added_by_id')->references('id')->on('admins');
            $table->unsignedBigInteger('verified_by_id')->nullable();
            $table->foreign('verified_by_id')->references('id')->on('admins');
            $table->enum('in_out', ['in', 'out']);
            $table->text('details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fianances', function (Blueprint $table) {
            if (Schema::hasColumn('fianances', 'added_by_id')) {
                $table->dropForeign('fianances_added_by_id_foreign');
                $table->dropForeign('fianances_verified_by_id_foreign');
                $table->dropColumn('added_by_id');
                $table->dropColumn('verified_by_id');
                $table->dropColumn('in_out');
                $table->dropColumn('details');
            }
        });
    }
}
