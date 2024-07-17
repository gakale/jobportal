<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'subscription_plan_id',
        'trial_ends_at',
        'ends_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function isTrial(){

        return $this->trial_ends_at && now()->lessThan($this->trial_ends_at);
    }

    public function isActive(){
        
        return $this->ends_at || now()->lessThan($this->ends_at);
    }   
}
