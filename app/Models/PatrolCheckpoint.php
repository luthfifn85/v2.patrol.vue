<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatrolCheckpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'patrol_location_id',
        'uuid',
        'name',
        'mode',
        'photo',
        'latitude',
        'longitude',
        'radius',
        'is_active'
    ];

    public function patrolTransactions()
    {
        return $this->hasMany(Patrol::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function patrolLocation()
    {
        return $this->belongsTo(PatrolLocation::class);
    }
}
