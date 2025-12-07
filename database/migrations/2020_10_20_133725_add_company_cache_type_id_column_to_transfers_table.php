<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyCacheTypeIdColumnToTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table) {
            // company_cache_type_id
            $table->unsignedBigInteger('company_cache_type_id')->nullable();
            $table->foreign('company_cache_type_id')->references('id')->on('company_cache_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign('transfers_company_cache_type_id_foreign');
            $table->dropColumn('company_cache_type_id');
        });
    }
}
