<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    protected $fillable = [
        'merk_id',
        'type_name',
    ];

    public function merks()
    {
        return $this->hasMany(Merk::class);
    }
}
