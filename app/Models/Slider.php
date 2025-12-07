<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasTranslations;
    public $translatable = ['title', 'image'];
    protected $table='sliders';
    protected $fillable = [
        'image', 'title', 'type'
    ];
}
