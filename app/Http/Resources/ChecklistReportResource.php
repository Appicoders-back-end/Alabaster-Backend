<?php

namespace App\Http\Resources;

use App\Http\Resources\Contractor\Checklist\ChecklistItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $total_items = $this->items->count();
        $items_done = $this->items->where('is_completed', 1)->count();
        $items_not_done = $this->items->where('is_completed', 0)->count();
        $completed_percent = $items_done > 0 ? ($items_done * 100) / $total_items : 0; //calculate complete percentage

        return [
            'id' => $this->id,
            'job_id' => $this->job->id,
            'name' => $this->name,
            'completed_by' => $this->job->cleaner != null ? $this->job->cleaner->name : null,
            'job_location' => $this->job->location,
            'status' => $this->status,
            'standard_status' => $this->standard_status,
            'total_items' => $total_items,
            'items_done' => $items_done,
            'items_not_done' => $items_not_done,
            'completed_percent' => $completed_percent . '%',
            'date' => $this->job->date,
            'items' => ChecklistItemResource::collection($this->items),
        ];
    }
}
