<?php

namespace App\Http\Resources\Contractor\Jobs;

use App\Http\Resources\Contractor\Customers\AddressesResource;
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
            'service_type' => $this->category ? $this->category->name : null,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'date' => $this->date,
            'time' => $this->time,
            'date_time' => $this->date_time,
            'urgency' => $this->urgency,
            'details' => $this->details,
        ];
    }
}
