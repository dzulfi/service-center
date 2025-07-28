<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'service_item_id',
    ];

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }
}
