<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SosComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sos_id',
        'patrol_user_id',
        'message'
    ];

    public function sos()
    {
        return $this->belongsTo(Sos::class);
    }

    public function patrolUser()
    {
        return $this->belongsTo(PatrolUser::class, 'patrol_user_id');
    }
}
