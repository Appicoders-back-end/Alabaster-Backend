<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending'; //assigned to cleaner but not started yet
    public const STATUS_WORKING  = 'working'; //started by cleaner
    public const STATUS_COMPLETED  = 'completed'; //completed

    public function scopeRequested($query)
    {
        return $query->whereStatus(WorkRequest::STATUS_REQUESTED);
    }

    public function scopeConfirmed($query)
    {
        return $query->whereStatus(WorkRequest::STATUS_CONFIRMED);
    }

    public function scopePending($query)
    {
        return $query->whereStatus(Task::STATUS_PENDING);
    }

    public function scopeWorking($query)
    {
        return $query->whereStatus(Task::STATUS_WORKING);
    }

    public function scopeCompleted($query)
    {
        return $query->whereStatus(Task::STATUS_COMPLETED);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function cleaner()
    {
        return $this->belongsTo(User::class, 'cleaner_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(UserAddress::class, 'address_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
