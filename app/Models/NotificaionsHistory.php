<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificaionsHistory extends Model
{
    protected $table='notificaions_histories';
    protected $fillable = [
        'title' , 'description' , 'drivers' , 'companies' , 
    ];
}
