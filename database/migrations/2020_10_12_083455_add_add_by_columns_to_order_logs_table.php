<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddByColumnsToOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_logs', function (Blueprint $table) {
            $table->string('added_by_type',50)->nullable();
            $table->integer('added_by_id')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_logs', function (Blueprint $table) {
            if (Schema::hasColumn('order_logs', 'added_by_type')) {
                $table->dropColumn('added_by_type');
                $table->dropColumn('added_by_id');
            }
        });
    }
}
