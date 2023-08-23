<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkRequest;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function getWorkRequest(Request $request)
    {
        $baseJobs = WorkRequest::with(['customer.company', 'contractor', 'category', 'location']);
        $baseJobs->when(request('search'), function ($query) use ($request) {
            return $query->where(function ($where) use ($request) {
                $where->whereHas('customer.company', function ($whereCompany) use ($request) {
                    $whereCompany->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('customer', function ($whereCustomer) use ($request) {
                    $whereCustomer->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('contractor', function ($whereContractor) use ($request) {
                    $whereContractor->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('category', function ($whereCategory) use ($request) {
                    $whereCategory->where('name', 'like', '%' . $request->search . '%');
                })->orWhere('request_no', 'like', '%' . $request->search . '%');
            });
        });
        $jobs = $baseJobs->orderBy('id', 'desc')->paginate(10);

        return view('admin.job.work-order-list', ['jobs' => $jobs]);
    }
}
