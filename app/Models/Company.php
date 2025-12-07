<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table='companies';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'city_id' , 'adress_details' , 'commercial_record' , 'active' ,
        'inside_price' , 'outside_price' , 'inside_delivery' , 'outside_delivery' , 'inside_payment_method' ,
        'outside_payment_method','image' , 'return_cost', 'latitude', 'longitude', 'address', 'c_o_d_cost'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];


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
    public function AvailableMethodCompany()
    {
        return $this->Hasmany(AvailableMethodCompany::class, 'company_id');
    }
    public function CompanyCacheType()
    {
        return $this->Hasmany(CompanyCacheType::class, 'company_id');
    }
    public function CompanyAddress()
    {
        return $this->Hasmany(CompanyAddress::class, 'company_id');
    }
    public function Order()
    {
        return $this->Hasmany(Order::class, 'company_id');
    }
    public function BranchData()
    {
        return $this->morphOne(BranchData::class, 'taggable');
    }

    public function City()
    {
        return $this->belongsto(City::class, 'city_id');
    }

    public function Transfer()
    {
        return $this->Hasmany(Transfer::class, 'company_id');
    }
    public function getImageAttribute($img)
    {
        return getImage($img);
    }
    /**
     * Get all of the post's Files.
     */
    public function Files()
    {
        return $this->morphMany(Files::class, 'taggable');
    }
    /**
     * Get all of the post's Files.
     */
    public function PlayerId()
    {
        return $this->morphMany(PlayerId::class, 'taggable');
    }
    public function CompanyCity()
    {
        return $this->Hasmany(CompanyCity::class, 'company_id');
    }
    public function CompanyCityGroup()
    {
        return $this->Hasmany(CompanyCityGroup::class, 'company_id');
    }
}
