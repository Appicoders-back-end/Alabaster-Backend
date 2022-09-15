<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkRequest;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function getWorkRequest(Request $request)
    {
        $baseJobs = WorkRequest::with(['customer', 'contractor', 'category', 'location']);
        $jobs = $baseJobs->orderBy('id', 'desc')->paginate(10);

        return view('admin.job.work-order-list', ['jobs' => $jobs]);
    }
}
