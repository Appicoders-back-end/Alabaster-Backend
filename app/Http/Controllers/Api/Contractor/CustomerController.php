<?php

namespace App\Http\Controllers\Api\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Http\Resources\Contractor\Customers\CustomersDetail;
use App\Http\Resources\Contractor\Customers\CustomersListResource;
use App\Mail\UserCreated;
use App\Models\User;
use App\Models\UserAddress;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $baseCustomers = User::where('role', User::Customer)->where('created_by', auth::user()->id);
        $customers = $baseCustomers->paginate(2);
        $customers = CustomersListResource::collection($customers)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $customers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => 'required|email|unique:users,email',
            'contact_no' => 'required|numeric'
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
            $user->role = User::Customer;
            $user->created_by = Auth::user()->id;
            $user->save();
            $user->markEmailAsVerified(true); //todo will be committed after signup process completed
            if (count($request->addresses) > 0) {
                foreach ($request->addresses as $address) {
                    $newAddress = new UserAddress();
                    $newAddress->user_id = $user->id;
                    $newAddress->street = $address['street'];
                    $newAddress->state = $address['state'];
                    $newAddress->zipcode = $address['zipcode'];
                    $newAddress->save();
                }
            }
//            Mail::to($request->email)->send(new UserCreated($user, $code)); //todo will be committed after signup process completed
            $user->code = $code;
            return apiResponse(true, __('Customer has been created successfully'), $user);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocations($id)
    {
        $user = User::find($id);
        if (!$user) {
            return apiResponse(false, __('User not found'));
        }
        $locations = UserAddress::where('user_id', $id)->get();
        $locations = AddressesResource::collection($locations);
        return apiResponse(true, __('Data loaded successfully'), $locations);
    }
}
