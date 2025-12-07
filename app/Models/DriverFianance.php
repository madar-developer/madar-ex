<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverFianance extends Model
{
    protected $table='driver_fianances';
    protected $fillable = [
        'branch_id', 'driver_id', 'total_amount' , 'driver_amount' , 'net_profit' ,
        'orders' , 'status' , 'verified'
    ];
    public static function getLevels($status)
    {
        $levels = [];
        switch ($status) {
            case 'init':
                $levels = ['init' => 'جاهز للتحصيل  ','collected_by_branch' => 'تم التحصيل بواسطة الفرع'];
                break;
            case 'collected_by_branch':
                $levels = ['collected_by_branch' => 'تم التحصيل بواسطة الفرع'];
                break;
        }
        return $levels;
    }
    public static function getDriverLevels($status)
    {
        $levels = [];
        switch ($status) {
            case '0':
                $levels = ['0' => 'جاهز للتحصيل من السائق','1' => 'تم التحصيل من السائق'];
                break;
            case '1':
                $levels = ['1' => 'تم التحصيل من السائق'];
                break;
        }
        return $levels;
    }
    public function Admin()
    {
        return $this->belongsto(Admin::class, 'branch_id');
    }
    public function Driver()
    {
        return $this->belongsto(Driver::class, 'driver_id');
    }
    public function OrdersNetProfit()
    {
        return Order::whereIn('id', explode(',', $this->orders))->sum('price');
    }
}
