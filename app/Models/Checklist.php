<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    public const STATUS_UNASSIGNED = 'unassigned';
    public const STATUS_ASSIGNED = 'assigned';

    public function items()
    {
        return $this->hasMany(Checklist::class, 'parent_id', 'id');
    }

    public function job()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');c
    }
}
