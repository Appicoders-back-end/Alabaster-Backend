<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['sender_id', 'reciever_id', 'title', 'message', 'content_id', 'content_type', 'deleted_at'];


    public function sender(){
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }


    public function reciever(){
        return $this->belongsTo(User::class, 'reciever_id', 'id');
    }
}
