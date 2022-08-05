<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Jobs\JobsDetailResource;
use App\Http\Resources\Contractor\Jobs\JobsListResource;
use App\Http\Resources\WorkOrder\WorkOrderDetail;
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
        $jobs = Task::where('contractor_id', auth()->user()->id)->paginate(10);
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
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
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
            return apiResponse(false, 'This request is only accessable for contractor type user');
        }

        try {
            $job = new Task();
            $job->customer_id = $request->customer_id;
            $job->address_id = $request->address_id;
            $job->contractor_id = auth()->user()->id;
            $job->category_id = $request->category_id;
            $job->date = $request->date;
            $job->start_time = date("H:i", strtotime($request->start_time));
            $job->end_time = date("H:i", strtotime($request->end_time));
            $job->start_date_and_time = date('Y-m-d H:i:s', strtotime($job->date . ' ' . $job->start_time));
            $job->store_id = $request->store_id;
            $job->store_address_id = $request->store_address_id;
            $job->status = Task::STATUS_PENDING;
            $job->details = $request->details;
            $job->urgency = $request->urgency;
            $job->details = $request->details;
            $job->shift = $request->shift;
            $job->save();

            if (isset($request->work_request_id) && $request->work_request_id != null) {
                $workrequest = WorkRequest::find($request->work_request_id);
                if (!$workrequest) {
                    return apiResponse(false, 'Work request not found');
                }
                $workrequest->status = WorkRequest::STATUS_CONFIRMED;
                $workrequest->save();

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
}
