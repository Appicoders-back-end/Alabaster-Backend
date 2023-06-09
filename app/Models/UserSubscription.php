<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'price',
        'payment_method_id',
        'stripe_charge_id',
        'start_date',
        'end_date',
        'is_expired',
        'inapp_plan_id',
    ];

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Subscription::class, 'plan_id', 'id');
    }
}
