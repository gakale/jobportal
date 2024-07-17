<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'trial_days',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
