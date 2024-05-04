<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question', 'options', 'answer', 'currect_answer', 'points', 'course_id'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
}
