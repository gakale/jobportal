<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'logo',
        'website',
        'email',
        'password',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class);
    }


    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function hasActiveSubscription()
    {
        $subscription = $this->subscription;

        if ($subscription) {
            return $subscription->isActive();
        }

        return false;
    }

    public function isOnTrial()
    {
        $subscription = $this->subscription;

        if ($subscription) {
            return $subscription->isTrial();
        }

        return false;
    }
}

