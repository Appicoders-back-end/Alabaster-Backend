<?php

namespace App\Http\Resources\Contractor\Jobs;

use App\Http\Resources\Contractor\Checklist\ChecklistResource;
use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Http\Resources\TaskInventoryResource;
use App\Models\Checklist;
use Illuminate\Http\Resources\Json\JsonResource;

class JobsDetailResource extends JsonResource
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
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'before' => $this->before,
            'after' => $this->after,
            'break_in' => $this->break_in,
            'break_out' => $this->break_out,
            'customer_id' => $this->customer ? $this->customer->id : null,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'customer_image' => $this->customer ? $this->customer->profile_image : null,
            'cleaner_id' => $this->cleaner ? $this->cleaner->id : null,
            'cleaner_name' => $this->cleaner ? $this->cleaner->name : null,
            'cleaner_image' => $this->cleaner ? $this->cleaner->profile_image : null,
            'location' => $this->location ? new AddressesResource($this->location) : null,
            'service_name' => $this->category ? $this->category->name : null,
            'urgency' => $this->urgency,
            'details' => $this->details,
            'status' => $this->status,
            'note' => $this->note,
            'report_problem' => $this->report_problem,
            'shift' => $this->shift,
            'lunch_start_time' => $this->lunch_start_time,
            'lunch_end_time' => $this->lunch_end_time,
            'before_lunch_image' => $this->before_lunch,
            'after_lunch_image' => $this->after_lunch,
            'estimated_break_hours' => "20 minutes", //TODO will dynamic soon
            'break_hours' => "30 minutes", //TODO will dynamic soon
            'estimated_working_hours' => "2 hours", //TODO will dynamic soon
            'working_hours' => "3 days 45 minutes", //TODO will dynamic soon
            'rating' => $this->rating,
            'time_in_latitude' => $this->time_in_latitude,
            'time_in_longitude' => $this->time_in_longitude,
            'time_out_latitude' => $this->time_out_latitude,
            'time_out_longitude' => $this->time_out_longitude,
            'lunch_in_latitude' => $this->lunch_in_latitude,
            'lunch_in_longitude' => $this->lunch_in_longitude,
            'lunch_out_latitude' => $this->lunch_out_latitude,
            'lunch_out_longitude' => $this->lunch_out_longitude,
            'inventories' => TaskInventoryResource::collection($this->inventories),
            'checklist' => ChecklistResource::collection(Checklist::with('items')->whereNull('parent_id')->where('task_id', $this->id)->get()),
        ];
    }
}
