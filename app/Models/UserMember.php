<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMember extends Model
{
    use HasFactory;

    protected $fillable = ['contractor_id', 'member_id', 'relation'];

    public function member(){
        return $this->belongsTo(User::class, 'member_id', 'id');
    }
}
