<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table='transfers';
    protected $fillable = [
        'transfer_number', 'amount', 'date_from' , 'date_to' , 'transfer_request_date' , 'image'  , 'active', 'company_id' ,'details' , 'date_to_higri' , 'date_from_higri',
        'total_price', 'company_price', 'madar_price' /** delivery_price */, 'collector', 'company_cache_type_id',
    ];
    protected $with = ['CompanyCacheType.AvailableMethod'];

    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function Invoice()
    {
        return $this->hasMany(Invoice::class, 'transfer_id');
    }
    public function TransferInvoice()
    {
        return $this->hasMany(TransferInvoice::class, 'transfer_id');
    }
    public function CompanyCacheType()
    {
        return $this->belongsTo(CompanyCacheType::class, 'company_cache_type_id');
    }
    public function BranchData()
    {
        return $this->morphOne(BranchData::class, 'taggable');
    }
}
