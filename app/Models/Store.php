<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public function locations()
    {
        return $this->hasMany(StoreAddress::class, 'store_id', 'id');
    }

    public function getImageUrl()
    {
        if ($this['image'] == null) {
            return null;
        }

        return url('/storage/uploads/') . '/' . $this['image'];
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'store_inventories', 'store_id', 'inventory_id')->withPivot('quantity');
    }
}
