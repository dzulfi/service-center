<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PivotRmaTechnician extends Model
{
    use HasFactory;

    protected $fillable = [
        'rma_technician_id',
        'service_item_id'
    ];

    public function rmaTechnician()
    {
        return $this->belongsTo(RmaTechnician::class, 'rma_technician_id');
    }

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }
}
