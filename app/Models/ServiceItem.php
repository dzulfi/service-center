<?php

namespace App\Models;

use App\Enums\ShipmentStatusEnum;
use App\Enums\ShipmentTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        // 'type',
        'serial_number',
        'code',
        // 'merk',
        'analisa_kerusakan',
        'jumlah_item',
        'created_by_user_id',
        'location_status', // untuk deteksi barang saat ini berada dimana
        'item_type_id'
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

    public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    // barang dikirim dari Admin cabang ke RMA
    public function getKirimKeRmaAttribute()
    {
        $toRma = $this->shipments()
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->first();

        return $toRma ? Carbon::parse($toRma->created_at) : null;
    }

    // Barang service diterima RMA dari Admin cabang
    public function getDiterimaRmaAttribute()
    {
        $received = $this->shipments()
            ->where('shipment_type', ShipmentTypeEnum::ToRMA)
            ->where('status', ShipmentStatusEnum::Diterima)
            ->first();

        return $received ? Carbon::parse($received->updated_at) : null;
    }

    // Barang service mulai dikerjakan RMA
    public function getMulaiDikerjakanAttribute()
    {
        $start = $this->serviceProcesses()->first();
        return $start ? Carbon::parse($start->created_at) : null;
    }

    // Barang service selesai
    public function getSelesaiDikerjakanAttribute()
    {
        $finish = $this->serviceProcesses()
            ->where('process_status', 'Selesai')
            ->first();

        return $finish ? Carbon::parse($finish->updated_at) : null;
    }

    // Barang service dikirim kembali dari RMA ke Admin cabang
    public function getDikirimKembaliAttribute()
    {
        $fromRma = $this->shipments()
            ->where('shipment_type', ShipmentTypeEnum::FromRMA)
            ->first();

        return $fromRma ? Carbon::parse($fromRma->created_at) : null;
    }

    // Barang service diterima kembali Admin cabang
    public function getDiterimaCabangAttribute()
    {
        $received = $this->shipments()
            ->where('shipment_type', ShipmentTypeEnum::FromRMA)
            ->where('status', ShipmentStatusEnum::DiterimaCabang)
            ->first();

        return $received ? Carbon::parse($received->updated_at) : null;
    }
}
