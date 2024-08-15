<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];
    protected $guard = 'student';

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function entries()
    {
        return $this->hasMany(Hasil::class, 'participant_id');
    }
}
