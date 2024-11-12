<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age', 'surnames'];

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'marks');
    }
}
