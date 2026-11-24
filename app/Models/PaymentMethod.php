<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PaymentMethod extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $table='payment_methods';
    protected $fillable = [
        'name',
        'parent',
        'required_action',
    ];

    public function Order()
    {
        return $this->Hasmany(Order::class, 'payment_method_id');
    }

    public function Parent()
    {
        return $this->belongsTo(PaymentMethod::class, 'parent');
    }

    public function Children()
    {
        return $this->hasMany(PaymentMethod::class, 'parent');
    }
}
