<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending'; //requested by customer but not reviewed and created by contractor
    public const STATUS_ACCEPT = 'accept'; //accepted and created by contractor
    public const STATUS_DECLINED = 'declined'; //declined by contractor

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

    public function location()
    {
        return $this->belongsTo(UserAddress::class, 'address_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function getInventoryAttribute()
    {
        if ($this->inventories == null) {
            return null;
        }

        $inventories = collect(json_decode($this->inventories));

        $inventoryArray = [];
        foreach ($inventories as $key => $inv) {
            $inventory = Inventory::find($inv->inventory_id);
            $inventoryArray[$key]['id'] = $inventory->id;
            $inventoryArray[$key]['name'] = $inventory->name;
            $inventoryArray[$key]['quantity'] = $inv->quantity;
        }
        return $inventoryArray;
    }
}
