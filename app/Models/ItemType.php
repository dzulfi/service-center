<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
    ];

    public function merk()
    {
        return $this->belongsTo(Merk::class, 'merk_id');
    }

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class,'item_type_id');
    }
}
