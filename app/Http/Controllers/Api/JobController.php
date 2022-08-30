<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChecklistReportResource;
use App\Http\Resources\Contractor\Checklist\ChecklistResource;
use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Http\Resources\Contractor\Jobs\AttendanceResource;
use App\Http\Resources\Contractor\Jobs\JobsDetailResource;
use App\Http\Resources\Contractor\Jobs\JobsListResource;
use App\Http\Resources\ProblemReportingResource;
use App\Http\Resources\WeeklyInspectionResource;
use App\Http\Resources\WorkOrder\WorkOrderDetail;
use App\Models\Checklist;
use App\Models\TaskInventory;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\WorkRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
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
        $baseJobs = Task::query();
        $baseJobs->when(auth()->user()->role == User::Contractor, function ($query) {
            return $query->where('contractor_id', auth()->user()->id);
        });
        $baseJobs->when($request->contractor_id, function ($query) use ($request) {
            return $query->where('contractor_id', $request->contractor_id);
        });
        $baseJobs->when(auth()->user()->role == User::Cleaner, function ($query) {
            return $query->where('cleaner_id', auth()->user()->id);
        });
        $baseJobs->when($request->cleaner_id, function ($query) use ($request) {
            return $query->where('cleaner_id', $request->cleaner_id);
        });
        $baseJobs->when(request('name'), function ($query) use ($request) {
            return $query->whereHas('customer', function ($customerQuery) use ($request) {
                $customerQuery->where('name', 'like', '%' . $request->name . '%');
            })->orWhereHas('cleaner', function ($cleanerQuery) use ($request) {
                $cleanerQuery->where('name', 'like', '%' . $request->name . '%');
            });
        });
        if (isset($request->status) && $request->status != null) {
            $request->status = strtolower($request->status);
            if ($request->status == 'upcoming') {
                $baseJobs = $baseJobs->where('start_time', '>', Carbon::now());
            } else if ($request->status == 'active') {
                $baseJobs = $baseJobs->whereIn('status', [Task::STATUS_PENDING, Task::STATUS_WORKING]);
            } else {
                $baseJobs = $baseJobs->whereStatus($request->status);
            }
        }
        if (isset($request->date) && $request->date != null) {
            $baseJobs = $baseJobs->whereDate('start_time', $request->date)->orWhereDate('time_in', $request->date);
        }
        $jobs = $baseJobs->paginate(2);
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
            'cleaner_id' => 'required',
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

            if ($request->inventories != null && count($request->inventories) > 0) {
                foreach ($request->inventories as $inventory) {
                    $newInventory = new TaskInventory();
                    $newInventory->task_id = $job->id;
                    $newInventory->inventory_id = $inventory['inventory_id'];
                    $newInventory->quantity = $inventory['quantity'];
                    $newInventory->save();
                }
            }

            if (isset($request->work_request_id) && $request->work_request_id != null) {
                $workRequest = WorkRequest::find($request->work_request_id);
                if (!$workRequest) {
                    return apiResponse(false, __('Work request not found'));
                }
                $workRequest->status = WorkRequest::STATUS_ACCEPT;
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
        $checklist = Checklist::with('items', 'job')->whereNull('parent_id')->paginate(10);
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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCheckList($id)
    {
        $checklist = Checklist::find($id);
        if (!$checklist) {
            return apiResponse(false, __('Checklist not found'));
        }
        $checklist->status = Checklist::STATUS_ASSIGNED;
        $checklist->save();
        return apiResponse(true, __('Checklist has been sent successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJobsByCleanerId(Request $request)
    {
        $baseJobs = Task::whereIn('status', [Task::STATUS_PENDING, Task::STATUS_WORKING]);
        if (isset($request->cleaner_id) && $request->cleaner_id != null) {
            $baseJobs = $baseJobs->where('cleaner_id', $request->cleaner_id);
        }

        if (isset($request->status) && $request->status != null) {
            $request->status = strtolower($request->status);
            if ($request->status == 'upcoming') {
                $baseJobs = $baseJobs->where('start_time', '>', Carbon::now());
            } else if ($request->status == 'active') {
                $baseJobs = $baseJobs->whereIn('status', [Task::STATUS_PENDING, Task::STATUS_WORKING]);
            } else {
                $baseJobs = $baseJobs->whereStatus($request->status);
            }
        }

        if (isset($request->pagination) && $request->pagination == strtolower('no')) {
            $jobs = $baseJobs->get();
            $jobs = JobsDetailResource::collection($jobs);
        } else {
            $jobs = $baseJobs->paginate(1);
            $jobs = JobsDetailResource::collection($jobs)->response()->getData(true);
        }

        return apiResponse(true, __('Record loaded successfully'), $jobs);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignJobToCleaner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|numeric',
            'cleaner_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        $job = Task::find($request->job_id);
        if (!$job) {
            return apiResponse(false, __('Job not found'));
        }
        $job->cleaner_id = $request->cleaner_id;
        $job->status = Task::STATUS_PENDING;
        $job->save();

        return apiResponse(true, __('Job has been assigned successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|numeric',
            'latitude' => 'required',
            'longitude' => 'required',
            'before_attachment' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        $job = Task::find($request->job_id);
        try {
            if ($request->file('before_attachment')) {
                $file = $request->file('before_attachment');
                $file_name = time() . "_" . $file->getClientOriginalName();
                $filename = pathinfo($file_name, PATHINFO_FILENAME);
                $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $file_name = str_replace(" ", "_", $filename);
                $file_name = str_replace(".", "_", $file_name) . "." . $extension;
                $path = public_path() . "/storage/uploads/";
                $file->move($path, $file_name);

                $job->before = $file_name;
            }
            $job->time_in_latitude = $request->latitude;
            $job->time_in_longitude = $request->longitude;
            $job->time_in = Carbon::now();
            $job->status = Task::STATUS_WORKING;
            $job->save();
        } catch (Exception $e) {
            return apiResponse(false, __('Something went wrong'), $e->getMessage());
        }

        return apiResponse(true, __('Job started successfully'), $job);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|numeric',
            'after_attachment' => 'required',
            'rating' => 'required'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        $job = Task::find($request->job_id);
        try {

            if ($request->file('after_attachment')) {
                $job->after = saveFile($request->file('after_attachment'));
            }

            $job->time_out = Carbon::now();
            $job->status = Task::STATUS_COMPLETED;
            $job->rating = $request->rating;
            $job->note = $request->note;
            $job->report_problem = $request->report_problem;
            $job->time_out_latitude = $request->time_out_latitude;
            $job->time_out_longitude = $request->time_out_longitude;
            $job->save();

            if (isset($request->checklists) && count($request->checklists) > 0) {
                foreach ($request->checklists as $checklist) {
                    $checklist = Checklist::find($checklist);
                    $checklist->is_completed = '1';
                    $checklist->save();
                }
            }
        } catch (Exception $e) {
            return apiResponse(false, __('Something went wrong'), $e->getMessage());
        }

        return apiResponse(true, __('Job ended successfully'), $job);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveLocations(Request $request)
    {
        $baseJobs = Task::whereNotNull('cleaner_id')->where('status', '!=', Task::STATUS_COMPLETED)->where('contractor_id', auth()->user()->id);
        $baseJobs->when(request('name'), function ($query) use ($request) {
            return $query->whereHas('cleaner', function ($customerQuery) use ($request) {
                $customerQuery->where('name', 'like', '%' . $request->name . '%');
            });
        });

        $baseJobs->when(request('status'), function ($query) use ($request) {
            return $query->whereStatus($request);
        });

        $jobs = $baseJobs->paginate(10);
        $jobs = JobsListResource::collection($jobs)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $jobs);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInventoryStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inventories' => 'required|array'
        ]);
        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        foreach ($request->inventories as $inventory) {
            $taskInventory = TaskInventory::where('id', $inventory['task_inventory_id'])->first();
            if (!$taskInventory) {
                continue;
            }
            $taskInventory->quantity_used = $inventory['quantity_used'];
            $taskInventory->save();
        }

        return apiResponse(true, __('Inventory has been updated successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function breakIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $job = Task::find($request->job_id);
            if ($request->file('before_lunch_attachment')) {
                $job->before_lunch_attachment = saveFile($request->file('before_lunch_attachment'));
            }
            $job->break_in = Carbon::now();
            $job->lunch_in_latitude = $request->latitude;
            $job->lunch_in_longitude = $request->longitude;
            $job->save();

            return apiResponse(true, __('Lunch time started successfully'));
        } catch (Exception $e) {
            return apiResponse(false, __('Something went wrong'), $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function breakOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $job = Task::find($request->job_id);
            if ($request->file('after_lunch_attachment')) {
                $job->after_lunch_attachment = saveFile($request->file('after_lunch_attachment'));
            }
            $job->break_out = Carbon::now();
            $job->lunch_out_latitude = $request->latitude;
            $job->lunch_out_longitude = $request->longitude;
            $job->save();

            return apiResponse(true, __('Lunch time out successfully'));
        } catch (Exception $e) {
            return apiResponse(false, __('Something went wrong'), $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function timeSheet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cleaner_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        $baseAttendance = Task::where('cleaner_id', $request->cleaner_id)->where('status', Task::STATUS_COMPLETED);
        if ((isset($request->date_from) && $request->date_from != null) && (isset($request->date_to) && $request->date_to != null)) {
            $baseAttendance = $baseAttendance->whereDate('time_out', '>=', $request->date_from)->whereDate('time_out', '<=', $request->date_to);
        }
        if ((isset($request->location_id) && $request->location_id != null)) {
            $baseAttendance = $baseAttendance->where('address_id', $request->location_id);
        }
        $attendance = $baseAttendance->paginate(2);
        $attendance = AttendanceResource::collection($attendance)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $attendance);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function problemReporting(Request $request)
    {
        $baseJobs = Task::where('contractor_id', auth()->user()->id)->completed();

        if ((isset($request->date_from) && $request->date_from != null) && (isset($request->date_to) && $request->date_to != null)) {
            $baseJobs = $baseJobs->whereDate('time_out', '>=', $request->date_from)->whereDate('time_out', '<=', $request->date_to);
        }
        if ((isset($request->location_id) && $request->location_id != null)) {
            $baseJobs = $baseJobs->where('address_id', $request->location_id);
        }

        $jobs = ProblemReportingResource::collection($baseJobs->paginate(2))->response()->getData(true);
        return apiResponse(true, __('Data loaded successfully'), $jobs);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function weeklyInspections(Request $request)
    {
        if (auth()->user()->role != User::Contractor) {
            return apiResponse(false, __('This request is only accessible for contractor type user'));
        }
        $baseJobs = Task::where('contractor_id', auth()->user()->id)->Completed();

        if ((isset($request->date_from) && $request->date_from != null) && (isset($request->date_to) && $request->date_to != null)) {
            $baseJobs = $baseJobs->whereDate('date', '>=', $request->date_from)->whereDate('date', '<=', $request->date_to);
        }

        $baseJobs->when($request->location_id, function ($query) use ($request) {
            return $query->where('address_id', $request->location_id);
        });

        $baseJobs->when($request->cleaner_id, function ($query) use ($request) {
            return $query->where('cleaner_id', $request->cleaner_id);
        });

        $baseJobs->when($request->customer_id, function ($query) use ($request) {
            return $query->where('customer_id', $request->customer_id);
        });

        $jobs = $baseJobs->paginate(10);

        $jobs = WeeklyInspectionResource::collection($jobs)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $jobs);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCompletedJobsLocations(Request $request)
    {
        if (auth()->user()->role != User::Contractor) {
            return apiResponse(false, __('This request is only accessible for contractor type user'));
        }

        $baseJobIds = Task::leftJoin('user_addresses as address', 'tasks.address_id', 'address.id')->where('contractor_id', auth()->user()->id)->completed();
        $baseJobIds->when($request->cleaner_id, function ($query) use ($request) {
            return $query->where('cleaner_id', $request->cleaner_id);
        });

        $locations = $baseJobIds->select('tasks.id as job_id', 'address.id', 'address.street', 'address.state', 'address.zipcode', DB::raw("CONCAT(address.street, ', ', address.state, ', ', address.zipcode) AS formatted_address"))->get();

        return apiResponse(true, __('Data loaded successfully'), $locations);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateChecklistRemark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checklist_id' => 'required|numeric',
            'remarks' => 'required'
        ]);

        if ($validator->fails()) {
            return apiResponse(false, implode("\n", $validator->errors()->all()));
        }

        $checklist = Checklist::find($request->checklist_id);
        if (!$checklist) {
            return apiResponse(false, __('Checklist not found'));
        }
        try {
            $checklist->remarks = $request->remarks;
            $checklist->save();

            return apiResponse(true, __('Remarks has been added successfully'));
        } catch (Exception $exception) {
            return apiResponse(false, __('Something went wrong'), $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checklistReports(Request $request)
    {
        $baseChecklist = Checklist::whereNull('parent_id')->whereHas('job', function ($query) {
            $query->where('status', Task::STATUS_COMPLETED);
        });

        if ((isset($request->date_from) && $request->date_from != null) && (isset($request->date_to) && $request->date_to != null)) {
            $baseChecklist = $baseChecklist->whereHas('job', function ($query) use ($request) {
                $query->whereDate('date', '>=', $request->date_from)->whereDate('date', '<=', $request->date_to);
            });
        }

        $baseChecklist->when($request->location_id, function ($query) use ($request) {
            return $query->whereHas('job', function ($jobQuery) use ($request) {
                $jobQuery->where('address_id', $request->location_id);
            });
        });

        $checklist = $baseChecklist->paginate(2);
        $checklist = ChecklistReportResource::collection($checklist)->response()->getData(true);

        return apiResponse(true, __('Record found'), $checklist);
    }
}
