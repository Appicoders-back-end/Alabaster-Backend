<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMember;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function getUsers(){
        $user = request()->user();

        $user = User::where('created_by', $user->id)->orderBy('created_at', 'DESC')->get();
        return apiResponse(true, 'Users list found', $user);
    }



    public function storeMember(Request $request){

        $data['contractor_id']      =       $request->user()->id;
        $data['member_id']          =       $request->member_id;
        $data['relation']          =       $request->relation;

        $member = UserMember::create($data);

        if($member){
            return apiResponse(true, 'Profile added successfully', $member);
        }

    }


    public function viewMembersList(){
        $user = request()->user();

        $user = UserMember::where('contractor_id', $user->id)->orderBy('created_at', 'DESC')->get();
        return apiResponse(true, 'Subscribed Users Found', $user);
    }
}
