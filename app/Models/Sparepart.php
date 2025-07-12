<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image_path',
        'description',
    ];

    protected $casts = [
        'stock_type' => \App\Enums\StockTypeEnum::class,
    ];

    public function stockSpareparts()
    {
        return $this->hasMany(StockSparepart::class, 'sparepart_id');
    }
}
