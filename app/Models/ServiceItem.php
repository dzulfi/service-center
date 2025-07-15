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
        'code',
        'merk',
        'analisa_kerusakan',
        'jumlah_item',
        'created_by_user_id',
        'location_status' // untuk deteksi barang saat ini berada dimana
    ];

    protected $casts = [
        'location_status' => \App\Enums\LocationStatusEnum::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceProcesses()
    {
        return $this->hasMany(ServiceProcess::class);
    }

    // relasi ke user yang membuat service ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getLatestServiceProcessAttribute()
    {
        return $this->serviceProcesses->sortByDesc('created_at')->first();
    }

    public function scopePendingOrInProgress($query)
    {
        return $query->whereDoesntHave('serviceProcesses', function ($q) {
            $q->whereIn('process_status', ['Selesai', 'Batal', 'Tidak bisa diperbaiki']);
        })->orWhereHas('serviceProcesses', function ($q) {
            $q->whereNotIn('process_status', ['Selesai', 'Batal', 'Tidak Bisa Diperbaiki']);
        });
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function stockSpareparts()
    {
        return $this->hasMany(StockSparepart::class, 'service_item_id');
    }
}
