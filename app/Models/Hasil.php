<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use MattDaneshvar\Survey\Models\Answer;
use MattDaneshvar\Survey\Models\Entry;

class Hasil extends Entry
{
    use SoftDeletes;
    protected $table = 'entries';

    public function student()
    {
        return $this->belongsTo(Student::class, 'participant_id');
    }

    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class, 'entry_id');
    }
}
