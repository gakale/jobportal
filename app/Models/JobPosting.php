<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPosting extends Model
{
    use HasFactory, Sluggable;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobPosting) {
            $company = Auth::guard('company')->user();
            if ($company) {
                $jobPosting->company_id = $company->id;
            } else {
                throw new \Exception('Company must be logged in to create a job posting.');
            }
        });
    }
    
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'application_link',
        'keywords',
        'company_id',
        'slug',
        'location',
        'score_threshold',
        'formatted_description',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
