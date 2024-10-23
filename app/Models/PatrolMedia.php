<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'patrol_id',
        'filename'
    ];

    public function patrol()
    {
        return $this->belongsTo(Patrol::class);
    }
}
