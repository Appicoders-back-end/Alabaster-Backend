<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function getUserNotificaions(){
        $user = request()->user();

        $notification = Notification::where('reciever_id', $user->id)->orderBy('created_at', 'DESC')->simplePaginate(10);
        return apiResponse(true, 'User Notifications', $notification);
    }
}
