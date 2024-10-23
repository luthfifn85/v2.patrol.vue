<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'patrol_location_id',
        'patrol_checkpoint_id',
        'date',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'time',
        'end_time' => 'time'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function patrolLocation()
    {
        return $this->belongsTo(PatrolLocation::class);
    }

    public function patrolCheckpoint()
    {
        return $this->belongsTo(PatrolCheckpoint::class);
    }
}
