<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getUserNotifications()
    {
        $user = request()->user();
        Notification::where('reciever_id', $user->id)->update([
            'is_read' => '0'
        ]);
        $notification = Notification::where('reciever_id', $user->id)->orderBy('created_at', 'DESC')->simplePaginate(10);
        triggerUnreadNotificationEvent();
        
        return apiResponse(true, 'User Notifications', $notification);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNotificationSetting(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        if ($user->is_receive_notification == '1') {
            $user->is_receive_notification = '0';
        } else {
            $user->is_receive_notification = '1';
        }
        $user->save();

        return apiResponse(true, __('Notification settings updated successfully'), $user);
    }
}
