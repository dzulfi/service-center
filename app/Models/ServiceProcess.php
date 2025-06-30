<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_item_id',
        'damage_analysis_detail',
        'solution',
        'process_status',
        'keterangan',
    ];

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }
}
