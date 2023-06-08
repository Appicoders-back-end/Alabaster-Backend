<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Task extends Model
{
    use HasFactory;

    public const STATUS_UNASSIGNED = 'unassigned'; //unassign this job to any cleaner
    public const STATUS_PENDING = 'pending'; //assigned to cleaner but not started yet
    public const STATUS_WORKING = 'working'; //started by cleaner
    public const STATUS_COMPLETED = 'completed'; //completed

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

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'task_inventories', 'task_id', 'inventory_id')->withPivot('id', 'quantity', 'quantity_used');
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class,  'task_id', 'id')->whereNull('parent_id');
    }

    public function getEstimatedTime()
    {
        $timeString = null;
        if ($this->start_time == null || $this->end_time == null) {
            return $timeString;
        }

        $datetime1 = new DateTime($this->start_time);
        $datetime2 = new DateTime($this->end_time);
        $interval = $datetime1->diff($datetime2);

        if ($interval->format('%d') > 0) {
            $timeString .= $interval->format('%d') . ' Days ';
        }
        if ($interval->format('%h') > 0) {
            $timeString .= $interval->format('%h') . ' Hours ';
        }
        if ($interval->format('%i') > 0) {
            $timeString .= $interval->format('%i') . ' Minutes ';
        }
//        $formattedDate = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
        return $timeString;
    }

    public function getCalculatedTotalTime()
    {
        $timeString = null;
        if ($this->time_in == null || $this->time_out == null) {
            return $timeString;
        }

        $datetime1 = new DateTime($this->time_in);
        $datetime2 = new DateTime($this->time_out);
        $interval = $datetime1->diff($datetime2);

        if ($interval->format('%d') > 0) {
            $timeString .= $interval->format('%d') . ' Days ';
        }
        if ($interval->format('%h') > 0) {
            $timeString .= $interval->format('%h') . ' Hours ';
        }
        if ($interval->format('%i') > 0) {
            $timeString .= $interval->format('%i') . ' Minutes ';
        }
//        $formattedDate = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
        return $timeString;
    }

    public function getEstimatedBreakTime()
    {
        $timeString = null;
        if ($this->lunch_start_time == null || $this->lunch_end_time == null) {
            return $timeString;
        }

        $datetime1 = new DateTime($this->lunch_start_time);
        $datetime2 = new DateTime($this->lunch_end_time);
        $interval = $datetime1->diff($datetime2);

        if ($interval->format('%d') > 0) {
            $timeString .= $interval->format('%d') . ' Days ';
        }
        if ($interval->format('%h') > 0) {
            $timeString .= $interval->format('%h') . ' Hours ';
        }
        if ($interval->format('%i') > 0) {
            $timeString .= $interval->format('%i') . ' Minutes ';
        }
//        $formattedDate = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
        return $timeString;
    }

    public function getCalculatedBreakTime()
    {
        $timeString = null;
        if ($this->break_in == null || $this->break_out == null) {
            return $timeString;
        }

        $datetime1 = new DateTime($this->break_in);
        $datetime2 = new DateTime($this->break_out);
        $interval = $datetime1->diff($datetime2);

        if ($interval->format('%d') > 0) {
            $timeString .= $interval->format('%d') . ' Days ';
        }
        if ($interval->format('%h') > 0) {
            $timeString .= $interval->format('%h') . ' Hours ';
        }
        if ($interval->format('%i') > 0) {
            $timeString .= $interval->format('%i') . ' Minutes ';
        }
//        $formattedDate = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
        return $timeString;
    }
}
