<?php

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::whereDoesntHave('OrderLog', function($q){
            $q->where('status' , 'init');
        })->get();
        foreach ($orders as $order) {
            
            //////////////////////// the date of creation order
            $order->OrderLog()->create([
                'status' => 'init',
                'details' =>  trans('words.'.'init'),
                'created_at' => $order->created_at
                ]);
            }
        
    }
}
