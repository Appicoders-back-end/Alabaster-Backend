<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Jobs\JobsListResource;
use App\Models\User;
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
        $jobs = Task::confirmed()->where('contractor_id', auth()->user()->id)->paginate(10);
        $jobs = JobsListResource::collection($jobs)->response()->getData(true);
        return apiResponse(true, __('Data loaded successfully'), $jobs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'date' => 'required',
            'time' => 'required',
            'store_id' => 'required',
            'urgency_id' => 'required',
            'location_id' => 'required',
            'customer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        $user = auth()->user();
        if ($user->role != User::Contractor) {
            return apiResponse(false, 'This request is only accessable in contractor type user');
        }
        try {
            if (isset($request->work_request_id) && $request->work_request_id != null) {
                $job = Task::find($request->work_request_id);
                if (!$job) {
                    return apiResponse(false, 'Work request not found');
                }
            } else {
                $job = new Task();
            }

            $job->customer_id = $request->customer_id;
            $job->contractor_id = auth()->user()->id;
            $job->category_id = $request->category_id;
            $job->date = $request->date;
            $job->time = date("H:i", strtotime($request->time));
            $job->date_time = date('Y-m-d H:i:s', strtotime($job->date . ' ' . $job->time));
            $job->store_id = $request->store_id;
            $job->address_id = $request->location_id;
            $job->status = Task::STATUS_CONFIRMED;
            $job->details = $request->details;
            $job->urgency_id = $request->urgency_id;
            $job->details = $request->details;
            $job->save();
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }

        return apiResponse(true, 'Job has been created successfully');
    }
}
