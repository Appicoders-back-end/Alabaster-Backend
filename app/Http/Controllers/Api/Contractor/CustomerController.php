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
        $customers = $baseCustomers->paginate(5);
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


    public function updateProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            // 'email' => 'required|email|unique:users,email,'. $request->user()->id,
            'contact_no' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {

            $data = $request->except(['profile_image', 'street', 'state', 'zipcode', 'addresses']);
            if($request->user()->role == User::Contractor || $request->user()->role == User::Customer){
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
            User::where('id', $request->user()->id)->update($data);
            // dd($user);
            // var_dump($data);die();
            if (isset($request->addresses) && count($request->addresses) > 0) {
                foreach ($request->addresses as $address) {
                    $newAddress = null;
                    if(isset($address['address_id'])){
                        $newAddress =  UserAddress::where('id', $address['address_id'])->first();
                    }

                    if($newAddress == null){
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
            return apiResponse(true, 'Profile has been updated successfully', $user);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }
}
