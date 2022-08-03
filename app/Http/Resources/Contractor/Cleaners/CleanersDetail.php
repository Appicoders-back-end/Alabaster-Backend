<?php

namespace App\Http\Resources\Contractor\Cleaners;

use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CleanersDetail extends JsonResource
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
            'name' => $this->name,
            'category_name' => $this->category ? $this->category->name : null,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'contact_no' => $this->contact_no,
            'working_start_time' => $this->working_start_time,
            'working_end_time' => $this->working_end_time,
            'addresses' => AddressesResource::collection($this->addresses)
        ];
    }
}
