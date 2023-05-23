<?php

namespace App\Http\Controllers\Api\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contractor\Customers\AddressesResource;
use App\Http\Resources\Contractor\Customers\CustomersListResource;
use App\Mail\UserCreated;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserSubscription;
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
        $customers = $baseCustomers->paginate(5);
        $customers = CustomersListResource::collection($customers)->response()->getData(true);

        return apiResponse(true, __('Data loaded successfully'), $customers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'email' => 'required|email|unique:users,email',
            'contact_no' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            if (auth()->user()->role == User::Contractor && !auth()->user()->hasMembership()) {
                return apiResponse(false, __('You have to buy membership first.'));
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
            $user->role = User::Customer;
            $user->created_by = Auth::user()->id;
            $user->save();
            $user->markEmailAsVerified(true);
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
            Mail::to($request->email)->send(new UserCreated($user, $code));
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


    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            // 'email' => 'required|email|unique:users,email,'. $request->user()->id,
            'contact_no' => 'required'
        ], [
            'contact_no.required' => 'Enter Contact Number',
            'contact_no.numeric' => 'Enter Valid Number'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $data = $request->except(['profile_image', 'street', 'state', 'zipcode', 'addresses']);
            if ($request->user()->role == User::Contractor || $request->user()->role == User::Customer) {
                unset($data['working_start_time']);
                unset($data['working_end_time']);
                unset($data['category_id']);
            }
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $fileName = time() . '.' . $request->file('profile_image')->getClientOriginalExtension();
                $featured_path = '../public/storage/uploads';
                $file->move($featured_path, $fileName);
                $data['profile_image'] = $fileName;
            }
            $data['name'] = sprintf("%s %s", $request->first_name, $request->last_name);
            User::where('id', $request->user()->id)->update($data);

            if (isset($request->addresses) && count($request->addresses) > 0) {
                foreach ($request->addresses as $address) {
                    $newAddress = null;
                    if (isset($address['address_id'])) {
                        $newAddress = UserAddress::where('id', $address['address_id'])->first();
                    }

                    if ($newAddress == null) {
                        $newAddress = new UserAddress();
                    }

                    // $newAddress = isset($address['address_id']) ? UserAddress::where('id', $address['address_id'])->first() : new UserAddress();
                    // dd($newAddress);
                    $newAddress->street = $address['street'];
                    $newAddress->user_id = $request->user()->id;
                    $newAddress->state = $address['state'];
                    $newAddress->zipcode = $address['zipcode'];
                    $newAddress->save();
                }
            }
            $user = User::where('id', $request->user()->id)->first();
            $user->addresses;
            $user->is_subscribed = UserSubscription::where('user_id', $user->id)->count() > 0 ? true : false;
            $user->category_name = $user->category ? $user->category->name : null;
            return apiResponse(true, 'Profile has been updated successfully', $user);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }
}
