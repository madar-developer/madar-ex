<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table='admins';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'active' , 'image' , 'lat' , 'long' , 'role' , 'city_id' , 'parent_id'
    ];
	protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = [
         'cities'
    ];
    public function UserRole()
    {
        return $this->Hasone(UserRole::class, 'admin_id');
    }
    public function DriverFianance()
    {
        return $this->Hasmany(DriverFianance::class, 'branch_id');
    }
    public function Fianance()
    {
        return $this->Hasmany(Fianance::class, 'branch_id');
    }
    public function City()
    {
        return $this->belongsto(City::class, 'city_id');
    }
    public function BranchCity()
    {
        return $this->Hasmany(BranchCity::class, 'branch_id');
    }
    public function Parent()
    {
        return $this->belongsto(Admin::class, 'parent_id');
    }
    public function getCitiesAttribute()
    {
        return $this->BranchCity()->pluck('city_id')->toArray();
    }

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
  

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    


  
    


    /**
     * Get all of the post's Files.
     */
  


    
  
}
