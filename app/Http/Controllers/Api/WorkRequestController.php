<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkOrder\WorkOrderList;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkRequestController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $baseTasks = Task::requested()->where('contractor_id', auth()->user()->id);
        $baseTasks->when(request('name'), function ($q) use ($request) {
            return $q->whereHas('customer', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        });
        $tasks = $baseTasks->paginate(10);
        $tasks = WorkOrderList::collection($tasks)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $tasks);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'date' => 'required',
            'time' => 'required',
            'store_id' => 'required',
            'urgency' => 'required',
            'location_id' => 'required',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        $user = auth()->user();
        if ($user->role != User::Customer) {
            return apiResponse(false, 'This request is only accessable in customer');
        }
        try {
            $task = new Task();
            $task->customer_id = $user->id;
            $task->contractor_id = $user->created_by;
            $task->category_id = $request->category_id;
            $task->date = $request->date;
            $task->time = date("H:i", strtotime($request->time));
            $task->date_time = date('Y-m-d H:i:s', strtotime($task->date . ' ' . $task->time));
            $task->store_id = $request->store_id;
            $task->address_id = $request->location_id;
            $task->status = Task::STATUS_REQUESTED;
            $task->details = $request->details;
            $task->urgency = $request->urgency;
            $task->details = $request->details;
            $task->save();
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }

        return apiResponse(true, 'Request has been created successfully');
    }
}
