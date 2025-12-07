<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table='users';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'full_address' , 'active' 
    ];



    public function Order()
    {
        return $this->Hasmany(Order::class, 'user_id');
    }

    public function Transfer()
    {
        return $this->Hasmany(Transfer::class, 'user_id');
    }

    public function BranchData()
    {
        return $this->morphOne(BranchData::class, 'taggable');
    }
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
    ];

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
