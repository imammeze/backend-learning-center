<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Program;
use App\Models\ProgramClass;

class Registration extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'student_id',
        'program_id',
        'program_class_id',
        'status',
        'registered_at',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function programClass()
    {
        return $this->belongsTo(ProgramClass::class);
    }
}
