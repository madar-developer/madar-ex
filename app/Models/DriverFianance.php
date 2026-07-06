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

    protected $casts = [
        'total_amount' => 'float',
        'driver_amount' => 'float',
        'net_profit' => 'float',
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
        return $this->codOrdersAmount();
    }

    public function codOrdersAmount()
    {
        if (!$this->orders) {
            return 0;
        }

        return (float) Order::whereIn('id', explode(',', $this->orders))
            ->where('payment_method_id', 1)
            ->sum('price');
    }

    public function recalculateTotals(): self
    {
        if (!$this->orders) {
            return $this;
        }

        $driver = $this->Driver()->first();
        if (!$driver) {
            return $this;
        }

        $orders = Order::whereIn('id', explode(',', $this->orders))
            ->with(['Company', 'Driver', 'Invoice'])
            ->get();

        if ($orders->isEmpty()) {
            return $this;
        }

        $totals = \App\Support\DriverFinance::batchTotals($orders, $driver);

        foreach ($orders as $order) {
            $commission = \App\Support\DriverFinance::driverCommission($order, $driver);
            $invoice = $order->relationLoaded('Invoice') ? $order->Invoice : $order->Invoice()->first();
            if ($invoice) {
                $invoice->update(['driver_cost' => $commission]);
            }
        }

        $this->update($totals);

        return $this->refresh();
    }
}
