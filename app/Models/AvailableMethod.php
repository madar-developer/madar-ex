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
        return $this->Hasmany(AvailableMethodCompany::class, 'available_method_id');
    }
    public function CompanyCacheType()
    {
        return $this->Hasmany(CompanyCacheType::class, 'available_method_id');
    }
}
