<?php

namespace App\Http\Resources\Contractor\Checklist;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistResource extends JsonResource
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
            'job_id' => $this->job->id,
            'name' => $this->name,
            'cleaner_id' => $this->job->cleaner != null ? $this->job->cleaner->id : null,
            'cleaner_name' => $this->job->cleaner != null ? $this->job->cleaner->name : null,
            'cleaner_profile_image' => $this->job->cleaner != null ? $this->job->cleaner->profile_image : null,
            'job_location' => $this->job->location,
            'status' => $this->status,
            'items' =>  ChecklistItemResource::collection($this->items),
        ];
    }
}
