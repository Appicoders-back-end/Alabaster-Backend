<?php

use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Stripe\StripeClient;

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

if (!function_exists('formattedTime')) {

    /**
     * @param $time
     * @return string|null
     */
    function formattedTime($time)
    {
        if (!$time) {
            return null;
        }
        return date('H:i A', strtotime($time));
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
            if (!$device_id || !file_exists(__DIR__ . '/../public/firebase.json')) {
                return false;
            }
            $factor = (new Factory())->withServiceAccount(__DIR__ . '/../public/firebase.json');
            $messaging = $factor->createMessaging();
            $message = CloudMessage::withTarget('token', $device_id)
                ->withNotification(Notification::create($title, $message));
            if ($data) {
                $message->withData($data);
            }
            $messaging->send($message);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}

if (!function_exists('saveFile')) {

    function saveFile($file)
    {
        $file_name = time() . "_" . $file->getClientOriginalName();
        $filename = pathinfo($file_name, PATHINFO_FILENAME);
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = str_replace(" ", "_", $filename);
        $file_name = str_replace(".", "_", $file_name) . "." . $extension;
        $path = public_path() . "/storage/uploads/";
        $file->move($path, $file_name);

        return $file_name;
    }
}

if (!function_exists('getTimeString')) {

    function getTimeString($interval)
    {
        // return $interval;
        $timeString = null;
        if ($interval->format('%d') > 0) {
            $timeString .= $interval->format('%d') . ' Days ';
        }

        if ($interval->format('%h') > 0) {
            $timeString .= $interval->format('%h') . ' Hours ';
        }

        if ($interval->format('%i') > 0) {
            $timeString .= $interval->format('%i') . ' Minutes ';
        }

        // $formattedDate = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
        return $timeString;
    }
}

if (!function_exists('getStripeCustomerId')) {

    function getStripeCustomerId($user)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        if ($user->stripe_customer_id != null) {
            return $user->stripe_customer_id;
        }
        $stripeCustomer = $stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
        ]);

        $stripe_customer_id = $stripeCustomer->id;
        $user->update(['stripe_customer_id' => $stripe_customer_id]);
        // $stripeCustomer = $this->stripe->customers->retrieve($stripe_customer_id);

        return $user->stripe_customer_id;
    }
}

if (!function_exists('formattedDate')) {

    /**
     * @param $date
     * @return string|null
     */
    function formattedDate($date)
    {
        if (!$date) {
            return null;
        }
        return date('m-d-Y', strtotime($date));
    }
}

if (!function_exists('generateRandomString')) {
    /**
     * @param $length
     * @return string
     */
    function generateRandomString($length = 20): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('generateRandomNumber')) {
    /**
     * @param $length
     * @return string
     */
    function generateRandomNumber($length = 6): string
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('triggerUnreadNotificationEvent')) {

    function triggerUnreadNotificationEvent($receiver_id = null)
    {
        if ($receiver_id == null) {
            $receiver_id = auth()->user()->id;
        }

        $unreadNotifications = \App\Models\Notification::where('reciever_id', $receiver_id)->where('is_read', 0)->count();
        broadcast(new \App\Events\UnreadNotifications($unreadNotifications, $receiver_id))->toOthers();
    }
}

if (!function_exists('formattedNumber')) {

    function formattedNumber($number)
    {
        $starting = "+1";
        if (substr($number, 0, 1) != "+1") {
            $number = str_replace("+1","", $number);
            $starting = null;
        }
        return $number != null ? $starting." (" . substr($number, 0, 3) . ") " . substr($number, 3, 3) . "-" . substr($number, 6) : '-';
        // return $number != null ? substr($number, 0, 2). " (" . substr($number, 3, 6) . ") " . substr($number, 6, 6) . "-" . substr($number, 9) : '-';
    }
}
