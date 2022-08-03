<?php

namespace App\Http\Resources\Contractor\Cleaners;

use App\Http\Resources\Categories\CategoriesListResource;
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
            'profile_image' => $this->profile_image,
            'category_name' => $this->category ? $this->category->name : null
        ];
    }
}
