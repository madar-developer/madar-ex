<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OrderStatus extends Model
{
    use HasTranslations;
    protected $table='order_statuses';
    public $translatable = ['name', 'details'];
    protected $fillable = [
        'key', 'name' , 'image' , 'details' , 'sort' , 'driver_active' , 'final_step' , 'color'
    ];

    public function getImageAttribute($img)
    {
        return getImage($img);
    }
}
