<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolUserLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patrol_user_id',
        'patrol_location_id'
    ];

    public function patrolUser()
    {
        return $this->belongsTo(PatrolUser::class);
    }

    public function patrolLocation()
    {
        return $this->belongsTo(PatrolLocation::class);
    }

    public function patrol()
    {
        return $this->hasMany(Patrol::class, 'patrol_location_id', 'patrol_location_id');
    }
}
