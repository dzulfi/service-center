<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    use HasFactory;

    protected $fillable = [
        'merk_name',
    ];

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class, 'merk_id');
    }
}
