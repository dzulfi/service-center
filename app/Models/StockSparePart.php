<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'description',
        'stock',
    ];
}
