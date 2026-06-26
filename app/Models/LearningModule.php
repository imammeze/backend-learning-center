<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LearningModule extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'program_id',
        'program_class_id',
        'teacher_id',
        'meeting_number',
        'title',
        'description',
        'file_path',
        'youtube_url',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function programClass()
    {
        return $this->belongsTo(ProgramClass::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
