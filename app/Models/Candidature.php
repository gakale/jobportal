<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id', // Ajoutez ce champ
        'job_posting_id',
        'status',
        'score_threshold',
        'comment',
    ];

    public function joinJobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }
}
