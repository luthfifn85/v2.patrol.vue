<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function patrolLocation()
    {
        return $this->belongsTo(PatrolLocation::class);
    }
}
