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

    // menghitung  total stok saat ini
    public function getStock()
    {
        $in = $this->stockSpareparts()->where('stock_type', 'in')->sum('quantity');
        $out = $this->stockSpareparts()->where('stock_type', 'out')->sum('quantity');

        return $in - $out;
    }
}
