<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolRole extends Model
{
    use HasFactory;

    PUBLIC CONST ROLE_ADMIN_ID = 1;
    PUBLIC CONST ROLE_CLIENT_ID = 4;

    protected $fillable = [
        'name',
        'is_active'
    ];
}
