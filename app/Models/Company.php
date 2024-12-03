<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    public const IMAGE_PATH = 'company';

    protected $fillable = [
        'name',
        'photo',
        'is_active'
    ];

    public function patrolLocations()
    {
        return $this->hasMany(PatrolLocation::class);
    }

    public function checkpoints()
    {
        return $this->hasMany(PatrolCheckpoint::class);
    }
}
