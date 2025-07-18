<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'branch_office_id',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // relasi ke branch office
    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class);
    }

    // relasi ke service item
    public function createdServiceItems()
    {
        return $this->hasMany(ServiceItem::class, 'created_by_user_id');
    }

    // relasi ke service process
    public function handleServiceProcesses()
    {
        return $this->hasMany(ServiceProcess::class, 'handle_by_user_id');
    }

    // Helper role
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isDeveloper()
    {
        return $this->hasRole('developer');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isRma()
    {
        return $this->hasRole('rma');
    }

    public function isRmaAdmin()
    {
        return $this->hasRole('rma_admin');
    }

    public function initiatedShipments()
    {
        return $this->hasMany(Shipment::class, 'responsible_user_id');
    }
}
