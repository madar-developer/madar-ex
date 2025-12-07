<?php

namespace App\Models;

use App\Http\Resources\Api\StatusResource;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Order extends Model
{
    protected $table='orders';
    protected $fillable = [
        'recipent_name', 'phone', 'city_id' , 'adress_details' , 'packages_number' , 'price'  , 'notes' , 'payment_method_id' ,
        'notes' , 'user_id' , 'company_id' , 'status' ,'refrence_no', 'serial', 'serial_no', 'driver_id', 'collected', 'signature',
        'latitude', 'longitude', 'description', 'receive_date', 'delivery_date', 'company_address_id' , 'weight' , 'district_id',
        'pick_up_date', 'cash_type', 'include_delivery_cost', 'order_type', 'return_packages', 'can_open', 'refrence_no_repeated'
    ];
    protected $appends = ['status_txt', 'status_image', 'status_color', 'available_statuses', 'company', 'owner_type', 'reason'];
    protected $with = ['PaymentMethod'];

    public static function getLevels($status)
    {
        $levels = [];
        switch ($status) {
            case 'new':
                $levels = ['init', 'not_received'];
                break;
            case 'not_received':
                $levels = ['init'];
                break;
            case 'init':
                $levels = ['at_madar', 'at_office'];
                break;
            case 'at_madar':
                $levels = ['at_office', 'returned'];
                break;
            case 'reschedule':
                $levels = ['at_office'];
                break;
            case 'at_office':
                $levels = ['deliver_failed', 'delivered', 'reschedule'];
                break;
            case 'deliver_failed':
                // $levels = ['delivered', 'returned', 'at_office', 'init'];
                $levels = ['returned', 'at_office'];
                break;
            case 'delivered':
                $levels = [];
                break;
            case 'cancelled':
                $levels = [];
                break;
            case 'returned':
                $levels = [];
                break;
        }
        return $levels;
    }
    public static function getLevelsW($status)
    {
        $levels = [];
        switch ($status) {
            case 'new':
                $levels = ['new','init', 'not_received'];
                break;
            case 'not_received':
                $levels = ['not_received', 'init'];
                break;
            case 'init':
                $levels = ['init', 'at_madar', 'at_office'];
                break;
            case 'at_madar':
                $levels = ['at_madar', 'at_office'];
                break;
            case 'reschedule':
                $levels = ['reschedule', 'at_office'];
                break;
            case 'at_office':
                $levels = ['at_office', 'deliver_failed', 'delivered', 'reschedule'];
                break;
            case 'deliver_failed':
                $levels = ['deliver_failed', 'delivered', 'returned', 'at_office', 'init'];
                break;
            case 'delivered':
                $levels = ['delivered'];
                break;
            case 'cancelled':
                $levels = ['cancelled'];
            case 'returned':
                $levels = ['returned'];
                break;
        }
        return $levels;
    }
    public function Invoice()
    {
        // return $this->hasMany(Invoice::class, 'order_id');
        return $this->hasOne(Invoice::class, 'order_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function CompanyAddress()
    {
        return $this->belongsTo(CompanyAddress::class, 'company_address_id');
    }
    public function Driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function District()
    {
        return $this->belongsTo(City::class, 'district_id');
    }
    public function PaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function OrderLog()
    {
        return $this->hasMany(OrderLog::class, 'order_id');
    }

    public function Transfer()
    {
        return $this->hasMany(Transfer::class, 'order_id');
    }
    public function BranchData()
    {
        return $this->morphOne(BranchData::class, 'taggable');
    }
    public function OrderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'status', 'key');
    }
    public function getStatusTxtAttribute()
    {
        if ($this->OrderStatus()->first()) {
            return $this->OrderStatus()->first()->name;
        } else {
            return '';
        }
    }
    public function getOwnerTypeAttribute()
    {
        return 'to';
    }
    public function getReasonAttribute()
    {
        if ($this->OrderLog()->where('active', '1')->where('status', 'deliver_failed')->first() && ! in_array($this->OrderLog()->where('active', '1')->where('status', 'deliver_failed')->first()->reason, ['', null])) {
            return deliverFailedOptions($this->OrderLog()->where('active', '1')->where('status', 'deliver_failed')->first()->reason);
        }
        return '';
    }
    public function getStatusImageAttribute()
    {
        if ($this->OrderStatus()->first()) {
            return $this->OrderStatus()->first()->image;
        } else {
            return '';
        }
    }
    public function getStatusColorAttribute()
    {
        if ($this->OrderStatus()->first()) {
            return $this->OrderStatus()->first()->color;
        } else {
            return '';
        }
    }
    public function getAvailableStatusesAttribute()
    {
        if (Auth::guard('api-driver')->user()) {
            $levels = Order::getLevels($this->status);
            $statuses = OrderStatus::whereIn('key', $levels)->select('id', 'key', 'name')->get();
            $statuses = StatusResource::collection($statuses);
            return $statuses;
        } else {
            return null;
        }
    }
    public function getCompanyAttribute()
    {
        return $this->Company()->select('id', 'phone', 'name', 'adress_details')->first();
    }

}
