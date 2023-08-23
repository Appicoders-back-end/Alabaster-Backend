<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $baseCompany = Company::where('contractor_id', auth()->user()->id);
            $baseCompany->when(request('name'), function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->name . '%');
            });
            $company = $baseCompany->orderByDesc('id')->paginate(10);
            $company = CompanyResource::collection($company)->response()->getData(true);

            return apiResponse(true, __('Data loaded successfully'), $company);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'contact_no' => 'required|max:20',
            'address' => 'required|max:191'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            if (auth()->user()->role != User::Contractor) {
                return apiResponse(false, __('This api is only accessible for contractor.'));
            }

            if (auth()->user()->role == User::Contractor && !auth()->user()->hasMembership()) {
                return apiResponse(false, __('You have to buy membership first.'));
            }

            $company = new Company();
            $company->name = $request->name;
            $company->address = $request->address;
            $company->contact_no = $request->contact_no;
            $company->website = $request->website;
            $company->contractor_id = auth()->user()->id;
            $company->save();

            return apiResponse(true, __('Company has been created successfully'), new CompanyResource($company));
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|max:191',
            'contact_no' => 'required|max:20',
            'address' => 'required|max:191'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            if (auth()->user()->role != User::Contractor) {
                return apiResponse(false, __('This api is only accessible for contractor.'));
            }

            if (auth()->user()->role == User::Contractor && !auth()->user()->hasMembership()) {
                return apiResponse(false, __('You have to buy membership first.'));
            }

            $company = Company::findOrFail($request->id);
            $company->name = $request->name;
            $company->address = $request->address;
            $company->contact_no = $request->contact_no;
            $company->website = $request->website;
            $company->save();

            return apiResponse(true, __('Company has been updated successfully'), new CompanyResource($company));
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage());
        }
    }

}
