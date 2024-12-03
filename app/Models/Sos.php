<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sos extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'patrol_location_id',
        'patrol_user_id',
        'title',
        'note',
        'latitude',
        'longitude',
        'views',
        'attachment'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function patrolLocation()
    {
        return $this->belongsTo(PatrolLocation::class, 'patrol_location_id');
    }

    public function patrolUser()
    {
        return $this->belongsTo(PatrolUser::class, 'patrol_user_id');
    }

    public function mediaItems()
    {
        return $this->hasMany(SosMedia::class, 'sos_id');
    }

    public function comments()
    {
        return $this->hasMany(SosComment::class, 'sos_id');
    }
}
