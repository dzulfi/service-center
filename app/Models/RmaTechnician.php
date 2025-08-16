<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmaTechnician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'no_telp'
    ];

    public function ServiceItems()
    {
        return $this->belongsToMany(ServiceItem::class, 'pivot_rma_technicians');
    }

    public function pivotRmaTechnicians()
    {
        return $this->hasMany(PivotRmaTechnician::class);
    }
}
