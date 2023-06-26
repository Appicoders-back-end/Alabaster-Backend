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
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'request_no' => $this->request_no,
            'customer_id' => $this->customer ? $this->customer->id : null,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'customer_image' => $this->customer ? $this->customer->profile_image : null,
            'customer_email' => $this->customer ? $this->customer->email : null,
            'customer_contact' => $this->customer ? $this->customer->contact_no : null,
            'cleaner_id' => $this->cleaner ? $this->cleaner->id : null,
            'cleaner_name' => $this->cleaner ? $this->cleaner->name : null,
            'cleaner_image' => $this->cleaner ? $this->cleaner->profile_image : null,
            'cleaner_email' => $this->cleaner ? $this->cleaner->email : null,
            'cleaner_contact' => $this->cleaner ? $this->cleaner->contact_no : null,
            'service_name' => $this->service_name,
            'service_type' => $this->category ? $this->category->name : null,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'before' => $this->before,
            'before_type' => $this->before_type,
            'after' => $this->after,
            'after_type' => $this->after_type,
            'estimated_working_hours' => $this->getEstimatedTime(),
            'working_hours' => $this->getCalculatedTotalTime(),
            'time_in_latitude' => $this->time_in_latitude,
            'time_in_longitude' => $this->time_in_longitude,
            'time_out_latitude' => $this->time_out_latitude,
            'time_out_longitude' => $this->time_out_longitude,
            'urgency' => $this->urgency,
            'details' => $this->details,
            'status' => $this->status,
            'inventories' => TaskInventoryResource::collection($this->inventories),
            'lunch_start_time' => $this->lunch_start_time,
            'lunch_end_time' => $this->lunch_end_time,
            'after_lunch_image' => $this->after_lunch_attachment,
            'lunch_in_latitude' => $this->lunch_in_latitude,
            'lunch_in_longitude' => $this->lunch_in_longitude,
            'lunch_out_latitude' => $this->lunch_out_latitude,
            'lunch_out_longitude' => $this->lunch_out_longitude
        ];
    }
}
