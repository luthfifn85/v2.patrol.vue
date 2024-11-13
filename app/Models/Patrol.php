<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'patrol_location_id',
        'patrol_checkpoint_id',
        'patrol_event_id',
        'patrol_user_id',
        'note',
        'latitude',
        'longitude',
        'views'
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

    public function patrolEvent()
    {
        return $this->belongsTo(PatrolEvent::class);
    }

    public function patrolUser()
    {
        return $this->belongsTo(PatrolUser::class);
    }

    public function comment()
    {
        return $this->hasMany(PatrolComment::class, 'patrol_id');
    }

    public function mediaItems()
    {
        return $this->hasMany(PatrolMedia::class, 'patrol_id');
    }
}
