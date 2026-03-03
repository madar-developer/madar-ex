<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderLog extends Model
{
    use SoftDeletes;

    protected $table='order_logs';
    protected $fillable = [
        'order_id', 'status' , 'details', 'added_by_type', 'added_by_id', 'reason', 'active', 'changed_from',
    ];
    protected $appends = [
        'image', 'color'
    ];
    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function ReasonD()
    {
        return $this->belongsTo(Term::class, 'reason');
    }
    public function getImageAttribute()
    {
        $row = OrderStatus::where('key', $this->status)->first();
        if ($row) {
            return $row->image;
        } else {
            return '';
        }

    }
    public function getColorAttribute()
    {
        $row = OrderStatus::where('key', $this->status)->first();
        if ($row) {
            return $row->color;
        } else {
            return '';
        }

    }
}
