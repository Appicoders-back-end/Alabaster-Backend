<?php

namespace App\Http\Resources\Contractor\Jobs;

use App\Http\Resources\Contractor\Customers\AddressesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobsDetailResource extends JsonResource
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
            'customer_id' => $this->customer ? $this->customer->id : null,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'customer_image' => $this->customer ? $this->customer->profile_image : null,
            'cleaner_id' => $this->cleaner ? $this->cleaner->id : null,
            'cleaner_name' => $this->cleaner ? $this->cleaner->name : null,
            'cleaner_image' => $this->cleaner ? $this->cleaner->profile_image : null,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'service_name' => $this->category ? $this->category->name : null,
            'urgency' => $this->urgency,
            'details' => $this->details,
            'status' => $this->status,
        ];
    }
}
