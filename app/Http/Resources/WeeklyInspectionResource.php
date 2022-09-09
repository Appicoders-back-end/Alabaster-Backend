<?php

namespace App\Http\Resources;

use App\Http\Resources\Contractor\Checklist\ChecklistResource;
use App\Http\Resources\Contractor\Customers\AddressesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyInspectionResource extends JsonResource
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
            'customer_id' => $this->customer ? $this->customer->id : null,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'customer_image' => $this->customer ? $this->customer->profile_image : null,
            'customer_email' => $this->customer ? $this->customer->email : null,
            'customer_contact' => $this->customer ? $this->customer->contact_no : null,
            'cleaner_id' => $this->cleaner ? $this->cleaner->id : null,
            'cleaner_name' => $this->cleaner ? $this->cleaner->name : null,
            'cleaner_image' => $this->cleaner ? $this->cleaner->profile_image : null,
            'service_type' => $this->category ? $this->category->name : null,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'date' => $this->date,
            'time' => $this->end_time,
            'estimated_working_hours' => "2 hours", //TODO will dynamic soon
            'working_hours' => "3 days 45 minutes", //TODO will dynamic soon
            'status' => $this->status,
            'contractor_comment' => $this->contractor_comment,
            'checklist' => $this->checklists->count() > 0 ? ChecklistResource::collection($this->checklists) : null,
        ];
    }
}
