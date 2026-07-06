<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE driver_fianances MODIFY total_amount DECIMAL(12,2) NULL');
        DB::statement('ALTER TABLE driver_fianances MODIFY driver_amount DECIMAL(12,2) NULL');
        DB::statement('ALTER TABLE driver_fianances MODIFY net_profit DECIMAL(12,2) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE driver_fianances MODIFY total_amount INT NULL');
        DB::statement('ALTER TABLE driver_fianances MODIFY driver_amount INT NULL');
        DB::statement('ALTER TABLE driver_fianances MODIFY net_profit INT NULL');
    }
};
