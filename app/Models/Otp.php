<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    protected $guarded = [];

    /** Relationship */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
