<?php

namespace App\Http\Controllers\Api\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Cleaners\CleanersListResource;
use App\Mail\UserCreated;
use App\Models\Task;
use App\Models\User;
use App\Models\UserAddress;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CleanerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (auth()->user()->role == User::Customer) {
            $completedJobsCleanerIds = Task::where('customer_id', auth()->user()->id)->where('status', Task::STATUS_COMPLETED)->pluck('cleaner_id')->toArray();
            $baseCleaners = User::whereIn('id', $completedJobsCleanerIds);
        } else {
            $baseCleaners = User::where('role', User::Cleaner)->where('created_by', auth()->user()->id);
        }

        $baseCleaners->when(request('name'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $baseCleaners->when(request('category_id'), function ($query) use ($request) {
            return $query->where('category_id', $request->category_id);
        });

        $cleaners = $baseCleaners->paginate(10);
        $cleaners = CleanersListResource::collection($cleaners)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $cleaners);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'email' => 'required|email|unique:users,email',
            'contact_no' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            if (auth()->user()->role == User::Contractor && !auth()->user()->hasMembership()) {
                return apiResponse(false, __('You have to buy membership first'));
            }

            $code = generateRandomString(8);
            $user = new User();
            $user->name = sprintf("%s %s", $request->first_name, $request->last_name);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($code);
            $user->remember_token = $code;
            $user->contact_no = $request->contact_no;
            $user->role = User::Cleaner;
            $user->created_by = auth()->user()->id;
            $user->category_id = $request->category_id;
            $user->working_start_time = $request->working_start_time;
            $user->working_end_time = $request->working_end_time;
            $user->save();
            $user->markEmailAsVerified(true);
            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->street = $request->street;
            $address->state = $request->state;
            $address->zipcode = $request->zipcode;
            $address->save();

//            if (count($request->categories) > 0) {
//                $user->categories()->attach($request->categories);
//            }

            Mail::to($request->email)->send(new UserCreated($user, $code));
            $user->code = $code;
            return apiResponse(true, __('Cleaner has been created successfully'), $user);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }

    /**
     * getting the list of idle cleaners
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveCleaners(Request $request)
    {
        $baseCleaners = User::where('role', User::Cleaner)->where('created_by', auth()->user()->id)->where('is_online', '1');
        $cleaners = $baseCleaners->paginate(10);
        $cleaners = CleanersListResource::collection($cleaners)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $cleaners);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'first_name' => ['required'],
            'contact_no' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $user = User::find($request->id);
            $user->name = sprintf("%s %s", $request->first_name, $request->last_name);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->contact_no = $request->contact_no;
            $user->category_id = $request->category_id;
            $user->working_start_time = $request->working_start_time;
            $user->working_end_time = $request->working_end_time;
            $user->save();
            $address = UserAddress::find($request->address_id);
            $address->user_id = $user->id;
            $address->street = $request->street;
            $address->state = $request->state;
            $address->zipcode = $request->zipcode;
            $address->save();

            return apiResponse(true, __('Cleaner has been updated successfully'), $user);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }
}
