<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id',
        'service_process_id',
        'stock_type',
        'quantity',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    public function serviceProcess()
    {
        return $this->belongsTo(ServiceProcess::class);
    }
}
