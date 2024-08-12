<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'date_naissance',
        'niveau_etude',
        'specialite',
        'experience',
        'cv',
        'lettre_motivation',
        'offre_id',
    ];
    public function offre()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function getCvAttribute($value)
    { 
    return asset('storage/' . $value);
    }
}
