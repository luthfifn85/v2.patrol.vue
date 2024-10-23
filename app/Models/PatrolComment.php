<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patrol_id',
        'patrol_user_id',
        'message'
    ];

    public function patrol()
    {
        return $this->belongsTo(Patrol::class);
    }

    public function patrolUser()
    {
        return $this->belongsTo(PatrolUser::class);
    }
}
