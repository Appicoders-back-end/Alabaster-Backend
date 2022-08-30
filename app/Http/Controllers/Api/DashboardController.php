<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Jobs\JobsDetailResource;
use App\Http\Resources\Contractor\Jobs\JobsListResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Task;

class DashboardController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContractorStats(Request $request)
    {
        if (auth()->user()->role != User::Contractor) {
            return apiResponse(false, 'This request is only accessible for contractor');
        }
        $job = Task::query()->where('contractor_id', auth()->user()->id)->orderBy('updated_at', 'DESC')->first();
        return apiResponse(true, __('Data loaded successfully'), new JobsDetailResource($job));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCleanerStats(Request $request)
    {
        if (auth()->user()->role != User::Cleaner) {
            return apiResponse(false, 'This request is only accessible for cleaner');
        }

        $jobs = Task::query()->where('cleaner_id', auth()->user()->id)->where('status', '!=', Task::STATUS_COMPLETED)->orderBy('updated_at', 'DESC')->get();
//        $activeJobs = $jobs->where('status', Task::STATUS_WORKING);
//        $nextJobs = $jobs->where('status', Task::STATUS_PENDING);

        $data['total'] = $jobs->count();
        $data['pending'] = $jobs->where('status', Task::STATUS_PENDING)->count();
        $data['working'] = $jobs->where('status', Task::STATUS_WORKING)->count();
        $data['completed'] = $jobs->where('status', Task::STATUS_COMPLETED)->count();
        $data['jobs'] = $jobs->count() == 0 ? null : JobsListResource::collection($jobs);
//        $data['next_jobs'] = $nextJobs->count() == 0 ? null : JobsListResource::collection($nextJobs);

        return apiResponse(true, __('Data loaded successfully'), $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerStats(Request $request)
    {
        if (auth()->user()->role != User::Customer) {
            return apiResponse(false, 'This request is only accessible for customer');
        }

        $jobs = Task::query()->where('customer_id', auth()->user()->id)->orderBy('updated_at', 'DESC')->get();
        $lastCompletedJob = Task::query()->where('customer_id', auth()->user()->id)->where('status', Task::STATUS_COMPLETED)->orderBy('updated_at', 'DESC')->first();
        $incompleteTasks = $jobs->where('status', '!=', Task::STATUS_COMPLETED);

        $data['total'] = $jobs->count();
        $data['pending'] = $jobs->where('status', Task::STATUS_PENDING)->count();
        $data['working'] = $jobs->where('status', Task::STATUS_WORKING)->count();
        $data['completed'] = $jobs->where('status', Task::STATUS_COMPLETED)->count();

        $data['last_completed_job'] = $lastCompletedJob == null ? null : new JobsDetailResource($lastCompletedJob);
        $data['assigned_jobs'] = $incompleteTasks->count() > 0 ? JobsListResource::collection($incompleteTasks) : null;

        return apiResponse(true, __('Data loaded successfully'), $data);
    }
}
