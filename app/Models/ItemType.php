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

    public function merk()
    {
        return $this->belongsTo(Merk::class, 'merk_id');
    }
}
