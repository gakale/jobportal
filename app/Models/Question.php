<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'choices',
        'correct_answer',
        'time_to_answer',
        "language"
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_questions');
    }
}
