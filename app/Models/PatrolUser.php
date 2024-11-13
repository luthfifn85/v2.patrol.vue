<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PatrolUser extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'company_id',
        'patrol_location_id',
        'patrol_role_id',
        'name',
        'photo',
        'username',
        'email',
        'mobile_no',
        'password',
        'is_login',
        'is_active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function patrolLocation()
    {
        return $this->belongsTo(PatrolLocation::class, 'patrol_location_id', 'id');
    }

    public function patrolRole()
    {
        return $this->belongsTo(PatrolRole::class);
    }

    public function patrol()
    {
        return $this->hasMany(Patrol::class);
    }

    public function location()
    {
        return $this->belongsTo(PatrolLocation::class, 'patrol_location_id');
    }
}
