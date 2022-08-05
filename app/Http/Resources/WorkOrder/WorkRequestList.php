<?php

namespace App\Http\Resources\WorkOrder;

use App\Http\Resources\Contractor\Customers\AddressesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkRequestList extends JsonResource
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
            'id' => $this->id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'datetime' => $this->date_time,
            'order_requested_date' => $this->created_at,
            'customer_id' => $this->customer ? $this->customer->id : null,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'customer_profile_image' => $this->customer ? $this->customer->profile_image : null,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'category_id' => $this->category ? $this->category->id : null,
            'category_name' => $this->category ? $this->category->name : null,
            'urgency' => $this->urgency,
            'details' => $this->details,
            'store_id' => $this->store ? $this->store->id : null,
            'store_name' => $this->store ? $this->store->name : null,
            'status' => $this->status,
        ];
    }
}
