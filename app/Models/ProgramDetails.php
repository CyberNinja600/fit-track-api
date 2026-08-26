<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'program_id', 'start_date', 'weeks', 'program_timeline', 'user_timeline', 'completion_status'])]
class ProgramDetails extends Model
{
    protected $with = ['program'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
