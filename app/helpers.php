<?php

use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


if (!function_exists('apiResponse')) {
    /**
     * @param boolean $status
     * @param string $msg
     * @param array|null $data
     * @param integer $http_status
     * @return \Illuminate\Http\JsonResponse
     */
    function apiResponse($status, $msg, $data = null, $http_status = 200)
    {
        return response()->json([
            'success' => $status,
            'message' => $msg,
            'data' => $data
        ], $http_status);
    }
}


if (!function_exists('SendNotification')) {
    /**
     * Send Notification to Device
     * @param string $device_id
     * @param string $title
     * @param string $message
     * @param null $data
     */
    function SendNotification($device_id, $title, $message, $data = null)
    {
        try {
            if ($device_id) {
                $factor = (new Factory())->withServiceAccount('firebase.json');
                $messaging = $factor->createMessaging();
                $message = CloudMessage::withTarget('token', $device_id)
                    ->withNotification(Notification::create($title, $message));
                if ($data) {
                    $message->withData($data);
                }
                $messaging->send($message);
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
