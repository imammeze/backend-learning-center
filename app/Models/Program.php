<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'name',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
