<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'sub_district',
        'district',
        'city'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
