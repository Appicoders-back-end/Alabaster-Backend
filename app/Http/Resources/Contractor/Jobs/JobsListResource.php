<?php

namespace App\Http\Resources\Contractor\Jobs;

use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Http\Resources\TaskInventoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobsListResource extends JsonResource
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
            'id'    => $this->id,
            'customer_id' => $this->customer ? $this->customer->id : null,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'customer_image' => $this->customer ? $this->customer->profile_image : null,
            'customer_email' => $this->customer ? $this->customer->email : null,
            'customer_contact' => $this->customer ? $this->customer->contact_no : null,
            'cleaner_id' => $this->cleaner ? $this->cleaner->id : null,
            'cleaner_name' => $this->cleaner ? $this->cleaner->name : null,
            'cleaner_image' => $this->cleaner ? $this->cleaner->profile_image : null,
            'service_type' => $this->category ? $this->category->name : null,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'estimated_working_hours' => "2 hours",
            'working_hours' => "3 days 45 minutes",
            'urgency' => $this->urgency,
            'details' => $this->details,
            'status' => $this->status,
            'inventories' => TaskInventoryResource::collection($this->inventories),
        ];
    }
}
