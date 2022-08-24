<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskInventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, //id from task_inventories table
            'name' => $this->name,
            'inventory_id' => $this->pivot->inventory_id, //id from inventories table
            'quantity' => $this->pivot->quantity,
            'quantity_used' => $this->pivot->quantity_used == null ? 0 : $this->pivot->quantity_used,
        ];
    }
}
