<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Checklist\ChecklistResource;
use App\Http\Resources\Contractor\Jobs\JobsDetailResource;
use App\Http\Resources\Contractor\Jobs\JobsListResource;
use App\Http\Resources\WorkOrder\WorkOrderDetail;
use App\Models\Checklist;
use App\Models\User;
use App\Models\WorkRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Exception;

class JobController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $jobs = Task::where('contractor_id', auth()->user()->id)->paginate(1);
        $jobs = JobsListResource::collection($jobs)->response()->getData(true);
        return apiResponse(true, __('Data loaded successfully'), $jobs);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'store_id' => 'required|numeric',
            'store_address_id' => 'required|numeric',
            'urgency' => 'required',
            'customer_id' => 'required',
            'address_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        $user = auth()->user();

        if ($user->role != User::Contractor) {
            return apiResponse(false, 'This request is only accessible for contractor type user');
        }

        try {
            $job = new Task();
            $job->customer_id = $request->customer_id;
            $job->address_id = $request->address_id;
            $job->contractor_id = auth()->user()->id;
            $job->category_id = $request->category_id;
            $job->date = $request->date;
            $job->start_time = date("Y-m-d H:i:s", strtotime($job->date . ' ' . $request->start_time));
            $job->end_time = date("Y-m-d H:i:s", strtotime($job->date . ' ' . $request->end_time));
            $job->store_id = $request->store_id;
            $job->store_address_id = $request->store_address_id;
            $job->status = Task::STATUS_PENDING;
            $job->details = $request->details;
            $job->urgency = $request->urgency;
            $job->lunch_start_time = date("Y-m-d H:i:s", strtotime($job->date . ' ' . $request->lunch_start_time));
            $job->lunch_end_time = date("Y-m-d H:i:s", strtotime($job->date . ' ' . $request->lunch_end_time));
            $job->shift = $request->shift;
            $job->cleaner_id = $request->cleaner_id;
            $job->save();

            if (isset($request->work_request_id) && $request->work_request_id != null) {
                $workRequest = WorkRequest::find($request->work_request_id);
                if (!$workRequest) {
                    return apiResponse(false, __('Work request not found'));
                }
                $workRequest->status = WorkRequest::STATUS_CONFIRMED;
                $workRequest->save();

                $job->work_request_id = $request->work_request_id;
                $job->save();
            }
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }

        return apiResponse(true, 'Job has been created successfully');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $job = Task::where('id', $id)->first();
        if (!$job) {
            return apiResponse(false, __('Record not found'));
        }
        $job = new JobsDetailResource($job);
        return apiResponse(true, __('Data loaded successfully'), $job);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllChecklist(Request $request)
    {
        $checklist = Checklist::with('items', 'job')->whereNull('parent_id')->paginate(1);
        $checklist = ChecklistResource::collection($checklist)->response()->getData(true);
        return apiResponse(true, __('Data loaded successfully'), $checklist);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createChecklist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'job_id' => 'required',
            'items' => 'required|array'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        if (count($request->items) == 0) {
            return apiResponse(false, __('Items are required'));
        }
        try {
            $checklist = new Checklist();
            $checklist->task_id = $request->job_id;
            $checklist->name = $request->name;
//            $checklist->description = $request->description;
            $checklist->status = Checklist::STATUS_UNASSIGNED;
            $checklist->save();

            foreach ($request->items as $item) {
                $newItem = new Checklist();
                $newItem->parent_id = $checklist->id;
                $newItem->name = $item['name'];
                $newItem->description = $item['description'];

                if (isset($item['attachment']) && $item['attachment'] != null) {
                    $file = $item['attachment'];
                    $file_name = time() . "_" . $file->getClientOriginalName();
                    $filename = pathinfo($file_name, PATHINFO_FILENAME);
                    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                    $file_name = str_replace(" ", "_", $filename);
                    $file_name = str_replace(".", "_", $file_name) . "." . $extension;
                    $path = public_path() . "/storage/uploads/";
                    $file->move($path, $file_name);

                    $newItem->attachment = $file_name;
                }
                $newItem->save();
            }
            return apiResponse(true, __('Record has been saved successfully'));
        } catch (Exception $exception){
            return apiResponse(false, $exception->getMessage());
        }
    }
}
