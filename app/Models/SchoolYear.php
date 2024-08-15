<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function rombels()
    {
        return $this->hasMany(Rombel::class);
    }
}
