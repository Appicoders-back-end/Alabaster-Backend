<?php

namespace App\Http\Resources\Contractor\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomersDetail extends JsonResource
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
            'name'  => $this->name,
            'email'  => $this->email,
            'profile_image'  => $this->profile_image,
            'contact_no'  => $this->contact_no,
            'addresses' => AddressesResource::collection($this->addresses)
        ];
    }
}
