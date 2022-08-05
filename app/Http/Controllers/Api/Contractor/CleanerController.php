<?php

namespace App\Http\Controllers\Api\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Cleaners\CleanersDetail;
use App\Http\Resources\Contractor\Cleaners\CleanersListResource;
use App\Mail\UserCreated;
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
        $baseCleaners = User::where('role', User::Cleaner)->where('created_by', auth()->user()->id);
        $baseCleaners->when(request('name'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });
        $baseCleaners->when(request('category_id'), function ($query) use ($request) {
            return $query->where('category_id', $request->category_id);
        });

        $cleaners = $baseCleaners->paginate(2);
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
            'name' => ['required'],
            'email' => 'required|email|unique:users,email',
            'contact_no' => 'required|numeric',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $code = rand(1111, 9999);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make('123456789');
            $user->remember_token = $code;
            $user->contact_no = $request->contact_no;
            $user->role = User::Cleaner;
            $user->created_by = auth()->user()->id;
            $user->category_id = $request->category_id;
            $user->working_start_time = $request->working_start_time;
            $user->working_end_time = $request->working_end_time;
            $user->save();
            $user->markEmailAsVerified(true); //todo will be committed after signup process completed
            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->street = $request->street;
            $address->state = $request->state;
            $address->zipcode = $request->zipcode;

//            if (count($request->categories) > 0) {
//                $user->categories()->attach($request->categories);
//            }

//            Mail::to($request->email)->send(new UserCreated($user, $code)); //todo will be committed after signup process completed
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
        $baseCleaners = User::where('role', User::Cleaner)->where('created_by', auth()->user()->id);
        $cleaners = $baseCleaners->paginate(2);
        $cleaners = CleanersListResource::collection($cleaners)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $cleaners);
    }
}
