<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    public const DURATION_WEEK = 'week';
    public const DURATION_MONTH = 'month';
    public const DURATION_YEAR = 'year';

    protected $fillable = [
        'package_name',
        'plan_id',
        'price',
        'interval_time',
        'description'
    ];
}
