<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('adress_details')->nullable();
            $table->string('commercial_record')->nullable();
            $table->integer('inside_price')->nullable();
            $table->integer('outside_price')->nullable();
            $table->boolean('inside_delivery')->default(0);
            $table->boolean('outside_delivery')->default(0);
            $table->string('inside_payment_method')->nullable();
            $table->string('outside_payment_method')->nullable();
            // $table->unsignedBigInteger('inside_payment_method_id')->nullable();
            // $table->foreign('inside_payment_method')->references('id')->on('payment_methods');
            // $table->unsignedBigInteger('outside_payment_method_id')->nullable();
            // $table->foreign('outside_payment_method_id')->references('id')->on('payment_methods');
            $table->boolean('active')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
        