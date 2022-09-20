<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAddress extends Model
{
    use HasFactory;

    public function getFormattedAddress()
    {
        return sprintf("%s, %s, %s", $this['street'], $this['state'], $this['zipcode']);
    }
}
