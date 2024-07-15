<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'job_posting_id',
        'number_of_questions',
        'duration',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'test_questions');
    }
}
