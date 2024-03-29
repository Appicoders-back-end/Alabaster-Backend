<?php

namespace App\Http\Resources\Contractor\Jobs;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Contractor\Customers\AddressesResource;
class AttendanceResource extends JsonResource
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
            'request_no' => $this->request_no,
            'customer_id' => $this->customer ? $this->customer->id : null,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'customer_image' => $this->customer ? $this->customer->profile_image : null,
            'customer_email' => $this->customer ? $this->customer->email : null,
            'customer_contact' => $this->customer ? $this->customer->contact_no : null,
            'cleaner_id' => $this->cleaner ? $this->cleaner->id : null,
            'cleaner_name' => $this->cleaner ? $this->cleaner->name : null,
            'cleaner_image' => $this->cleaner ? $this->cleaner->profile_image : null,
            'service_type' => $this->category ? $this->category->name : null,
            'service_name' => $this->service_name,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'date' => $this->date,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'job_time' => $this->getCalculatedTotalTime(),
            'break_time' => $this->getCalculatedBreakTime(),
            'status' => $this->status,
        ];
    }
}
