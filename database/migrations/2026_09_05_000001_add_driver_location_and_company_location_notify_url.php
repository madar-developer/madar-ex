<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverLocationAndCompanyLocationNotifyUrl extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            if (!Schema::hasColumn('drivers', 'last_latitude')) {
                $table->decimal('last_latitude', 10, 8)->nullable()->after('image');
            }
            if (!Schema::hasColumn('drivers', 'last_longitude')) {
                $table->decimal('last_longitude', 11, 8)->nullable()->after('last_latitude');
            }
            if (!Schema::hasColumn('drivers', 'last_location_at')) {
                $table->timestamp('last_location_at')->nullable()->after('last_longitude');
            }
        });

        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'location_notify_url')) {
                $table->string('location_notify_url')->nullable()->after('notify_url');
            }
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            foreach (['last_latitude', 'last_longitude', 'last_location_at'] as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'location_notify_url')) {
                $table->dropColumn('location_notify_url');
            }
        });
    }
}
