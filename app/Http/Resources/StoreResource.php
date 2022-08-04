<?php

namespace App\Http\Resources;

use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'image'  => $this->image,
            'locations' => AddressesResource::collection($this->locations),
        ];
    }
}
