<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable implements JWTSubject
{

    use Notifiable;
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $table='drivers';
    protected $fillable = [
        'first_name', 'last_name' , 'email' , 'phone' , 'identical_number' , 'password' , 'nationality' , 'license_number',
        'license_date_expiration' , 'car_id' , 'remember_token', 'identity_expiration_date', 'car_receive_date', 'type', 'commission',
        'identity_image' , 'license_image' , 'form_image' , 'car_receive_date_hijri' , 'identity_expiration_date_hijri',
        'license_expiration_date_hijri' , 'fixed_salary' , 'commission'
    ];
    protected $appends = [
        'cities', 'order_count', 'order_failed_count', 'order_delivered_count', 'delivering_orders_count', 'received_count'
    ];
/**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function BranchData()
    {
        return $this->morphMany(BranchData::class, 'taggable');
    }
    public function DriverFianance()
    {
        return $this->hasMany(DriverFianance::class, 'driver_id');
    }
    /**
     * Get all of the post's Files.
     */
    public function PlayerId()
    {
        return $this->morphMany(PlayerId::class, 'taggable');
    }
    public function Car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function DriverCity()
    {
        return $this->hasMany(DriverCity::class, 'driver_id');
    }

    public function Order()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function Invoice()
    {
        return $this->hasManyThrough(
            Invoice::class,
            Order::class,
            'driver_id', // Foreign key on orders table...
            'order_id', // Foreign key on invoices table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
        );
    }


    public function getCitiesAttribute()
    {
        return $this->DriverCity()->pluck('city_id')->toArray();
    }
    public function getOrderCountAttribute()
    {
        return $this->Order()->where('status', '<>', 'returned')->where('collected','<>', 1)->count();
    }
    public function getOrderDeliveryCountAttribute()
    {
        return $this->Order()->where('status', 'at_office')->where('collected','<>', 1)->count();
    }
    public function getOrderDeliveredCountAttribute()
    {
        return $this->Order()->where('status', 'delivered')->where('collected','<>', 1)->count();
    }
    public function getOrderFailedCountAttribute()
    {
        return $this->Order()->where('status', 'deliver_failed')->where('collected','<>', 1)->count();
    }
    public function getDeliveringOrdersCountAttribute()
    {
        return $this->Order()->where('status', 'at_office')->where('collected','<>', 1)->count();
    }
    public function getReceivedCountAttribute()
    {
        return $this->Order()->where('status', 'init')->whereDate('updated_at', Carbon::now()->toDateString())->where('collected','<>', 1)->count();
    }
    /**
     * Get all of the post's Files.
     */
    public function Files()
    {
        return $this->morphMany(Files::class, 'taggable');
    }

    public function DriverCityPrice()
    {
        return $this->hasMany(DriverCityPrice::class, 'driver_id');
    }
}
