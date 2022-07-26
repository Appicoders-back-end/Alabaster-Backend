<?php

namespace App\Http\Resources\Contractor\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressesResource extends JsonResource
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
            'street' => $this->street,
            'state' => $this->state,
            'zipcode' => $this->zipcode,
            'formated_address' => sprintf("%s, %s, %s", $this->street, $this->state, $this->zipcode),
        ];
    }
}
