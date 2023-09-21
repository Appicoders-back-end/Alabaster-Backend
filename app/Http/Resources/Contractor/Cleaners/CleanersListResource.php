<?php

namespace App\Http\Resources\Contractor\Cleaners;

use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Models\Task;
use Illuminate\Http\Resources\Json\JsonResource;

class CleanersListResource extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'profile_image' => $this->profile_image,
            'category_id' => $this->category ? $this->category->id : null,
            'category_name' => $this->category ? $this->category->name : null,
            'contact_no' => $this->contact_no,
            'working_start_time' => $this->working_start_time,
            'working_end_time' => $this->working_end_time,
            'break_time_in' => $this->break_time_in,
            'break_time_out' => $this->break_time_out,
            'address' => AddressesResource::collection($this->addresses),
            'is_idle' => Task::where('cleaner_id', $this->id)->where('status', '!=', Task::STATUS_COMPLETED)->count() > 0 ? false : true,
        ];
    }
}
