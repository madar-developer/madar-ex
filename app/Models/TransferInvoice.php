<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferInvoice extends Model
{
    protected $table='transfer_invoices';
    protected $fillable = [
                'invoice_id' , 'transfer_id'
    ];

}
