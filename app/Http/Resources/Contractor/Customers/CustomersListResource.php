<?php

namespace App\Http\Resources\Contractor\Customers;

use App\Http\Resources\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomersListResource extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'profile_image' => $this->profile_image,
            'contact_no' => $this->contact_no,
            'company' => $this->company != null ? new CompanyResource($this->company) : null,
            'addresses' => AddressesResource::collection($this->addresses)
        ];
    }
}
