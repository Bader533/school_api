<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
