<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatrolLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'address',
        'phone',
        'is_active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function officers()
    {
        return $this->hasMany(PatrolUser::class, 'patrol_location_id');
    }

    public function checkpoints()
    {
        return $this->hasMany(PatrolCheckpoint::class);
    }
}
