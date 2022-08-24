<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Jobs\JobsDetailResource;
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
        $job = Task::query()->where('contractor_id', auth()->user()->id)->orderBy('updated_at','DESC')->first();
        return apiResponse(true, __('Data loaded successfully'), new JobsDetailResource($job));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCleanerStats(Request $request)
    {
        $job = Task::query()->where('contractor_id', auth()->user()->id)->orderBy('updated_at','DESC')->first();
        return apiResponse(true, __('Data loaded successfully'), new JobsDetailResource($job));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerStats(Request $request)
    {
        $job = Task::query()->where('contractor_id', auth()->user()->id)->orderBy('updated_at','DESC')->first();
        return apiResponse(true, __('Data loaded successfully'), new JobsDetailResource($job));
    }
}
