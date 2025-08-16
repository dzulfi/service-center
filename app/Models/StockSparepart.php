<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id',
        'service_item_id',
        'stock_type',
        'quantity',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    // public function serviceProcess()
    // {
    //     return $this->belongsTo(ServiceProcess::class);
    // }

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }
}
