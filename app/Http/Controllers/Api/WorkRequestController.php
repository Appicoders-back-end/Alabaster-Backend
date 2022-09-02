<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Customers\CustomersListResource;
use App\Http\Resources\WorkOrder\WorkRequestList;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkRequestController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $id)
    {
        $baseTasks = WorkRequest::where('customer_id', $id);
        $baseTasks->when(request('name'), function ($query) use ($request) {
            return $query->where('id', 'like', '%' . $request->name . '%');
        });
        $tasks = $baseTasks->orderBy('id', 'DESC')->get();
        $tasks = WorkRequestList::collection($tasks);

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
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required',
            'store_id' => 'required',
            'urgency' => 'required',
            'address_id' => 'required',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        $user = auth()->user();
        if ($user->role != User::Customer) {
            return apiResponse(false, 'This request is only accessible for customer');
        }
        try {
            $task = new WorkRequest();
            $task->customer_id = $user->id;
            $task->address_id = $request->address_id;
            $task->contractor_id = $user->created_by;
            $task->category_id = $request->category_id;
            $task->date = $request->date;
            $task->start_time = date("H:i", strtotime($request->start_time));
//            $task->end_time = date("H:i", strtotime($request->end_time));
//            $task->date_time = date('Y-m-d H:i:s', strtotime($task->date . ' ' . $task->time));
            $task->store_id = $request->store_id;
            $task->store_address_id = $request->store_address_id;
            $task->status = WorkRequest::STATUS_PENDING;
            $task->details = $request->details;
            $task->urgency = $request->urgency;
            if ($request->inventories != null && count($request->inventories) > 0) {
                $task->inventories = json_encode($request->inventories);
            }
            $task->save();

            $user = auth()->user();
            $title = $user->name;
            $message = sprintf("%s sent you work order request", $user->name);
            SendNotification($user->device_id, $title, $message);

            Notification::create([
                'reciever_id' => $user->created_by,
                'sender_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'content_id' => $task->id,
                'content_type' => "work_order_request",
                'is_read' => 0
            ]);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }

        return apiResponse(true, 'Request has been created successfully');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function getWorkRequestCustomers(Request $request)
    {
        $workRequestsUserIds = WorkRequest::where('contractor_id', auth()->user()->id)->pluck('customer_id')->toArray();
        $baseCustomers = User::whereIn('id', $workRequestsUserIds);
        $baseCustomers->when(request('name'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });
        $customers = $baseCustomers->paginate(10);
        $customers = CustomersListResource::collection($customers);

        return apiResponse(true, __('Data loaded successfully'), $customers);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function declineWorkRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            WorkRequest::where('id', $request->id)->update(['status' => WorkRequest::STATUS_DECLINED]);
            return apiResponse(true, __('Request has been declined successfully'));
        } catch (\Exception $e) {
            return apiResponse(false, __('Something went wrong'), $e->getMessage());
        }
    }
}
