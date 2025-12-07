<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AvailableMethod extends Model
{
    use HasTranslations;
    public $translatable = ['title'];
    protected $table='available_methods';
    protected $fillable = [
        'title', 'image' , 'type' , 'active'

    ];

    public function AvailableMethodCompany()
    {
        return $this->hasMany(AvailableMethodCompany::class, 'available_method_id');
    }
    public function CompanyCacheType()
    {
        return $this->hasMany(CompanyCacheType::class, 'available_method_id');
    }
}
