<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProgramClass extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'program_id',
        'name',
        'min_age',
        'max_age',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
