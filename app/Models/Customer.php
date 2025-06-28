<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'company',
        'address',
        'kelurahan',
        'kecamatan',
        'kota',
    ];

    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }
}
