<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_item_id',
        'shipment_type',
        'resi_number',
        'responsible_user_id',
        'resi_image_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'shipment_type' => \App\Enums\ShipmentTypeEnum::class,
        'status' => \App\Enums\ShipmentStatusEnum::class,
    ];

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
