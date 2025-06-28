<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'type',
        'serial_number',
        'merk',
        'analisa_kerusakan',
        'jumlah_item'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
