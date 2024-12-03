<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SosMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'sos_id',
        'filename'
    ];

    public function sos()
    {
        return $this->belongsTo(Sos::class);
    }
}
