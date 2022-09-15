<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'street', 'state', 'zipcode'];

    public function getFormattedAddress()
    {
        return sprintf("%s, %s, %s", $this['street'], $this['state'], $this['zipcode']);
    }
}
