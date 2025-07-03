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
        'handle_by_user_id',
    ];

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handle_by_user_id');
    }
}
