<?php

use Illuminate\Database\Seeder;
use App\Models\Order;

class SerialNoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::get()->each(function($q){
            $q->update(['serial_no' => (int)str_replace('mx-', '', $q->serial)]);
        });
    }
}
