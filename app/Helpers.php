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
            if ($device_id) {
                $factor = (new Factory())->withServiceAccount('
                    "type": "service_account",
                    "project_id": "alabaster-a518d",
                    "private_key_id": "4a14e84637eab39a205a59c1b3395d81737ba576",
                    "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCiWdvxWmGz9zxR\njMKyk2SH+v8wPJW04Y9FGhFNS5XhhS2h9+8zuMGpSALJOSHb8nfghAjjCuPxGeMh\nzQdm0XLcqQzZkwASuk6SBCJqdCoKYxzqKLt6RVRAcvFKjnQ0RuSSfR8AqHy9GgVe\nOvVnqjQhUe49M4iMwx0ssx2nGRxRTV1oTPg6ixelKQR931qXXAKtBxb/MK+Yp/D4\nlNzGRxnrnCHErqe3kfoFhrsqDjVaXxDNohS6aOxm1Q0BAupjPj1O5PIHvGtsOhg8\nf0AUoJJds9FcmBeM98dyf6EvVW0v/bhd+5xeJqhMi0FhHbdf5wqMjW6nVZ51PL9A\nTrXraWBpAgMBAAECggEAB0cfs9S1RUnnoXC90B9JHgrnimgm3mRPpb1+ydAh/Il5\ns5VvmsoVHWNx44cdAFUA4HbOxq3H7NiYHN1p7+ci/ucRoUXElrJVIBjRGaRSN6O8\nibcwe1FVDuwQKsNSzH6ik7oJ7B/BcsEyPSiHtIFV46n+c+4l2IiNG+I+Ka3XngHX\nbqD3yoa0V/Zbt3JtMuESFkbNjHq+vcro955o8AwiV8B9ZJBD+HVmksGZAsPETgTD\nOY0t1urUTB/Xy2qH2+0JrJd32AjkfbUlySJ6NFCS3iCMTActKD7m3ZeWOpV/D1Au\nO9CEBBwHqBYmUD+E6SBJDqziN0kNAFDjxrAvtM6jcQKBgQDPVHDLwEvlKEGNQL+I\npvfGMS81L8N+Tlo/3IJaIMEDqgpYxYRniZ3IxrzxYn0/cxnmVJoCrWYGXx6L5mjH\nrPid6+sFInNJHtZyNG6FOGu52q7/aYjFSoz8S9VszPpF8rRphfaoLGpOVgBMHCtX\nOMuU7sXweTebjvWjqbNkRdvSeQKBgQDIdmZcCJNy9JIC9Z2A8IgNh6CbvNzDn8At\n3eCkC722U8u9r5r14sl1miMoTas/k0kFt4G/BZnNc04TouEKssuhv/Yx6JZTMFPd\nxm16+T4e9MNG/Z3iafj7XE1ljDeRFboEQ+KXwH8EEVUxHsBdzyV23IjW4fJJZ9bj\nVYSeKkcBcQKBgQCqlc1gt006tM6Ki/WLV+WRejJfTndI2urClInj4gMtIqD0vT7d\neYsLAQnAicqHhAqRSpaVEVpab7TQYrlfYOAF/3AVf2zfgrHqsV0l23A3MMi/eXOA\n8H/jrfliVZyuJK1wDTsOz8x/u57vAFZgo2hNl8/gtbudEBjnmd7x59Cf8QKBgQCf\n0hXFpmi4K9kFVwEdvaca8ljQEYEIziKP7uecome8J462Sn0HMKphgmQpS9MOOsTr\nM+TTgQmgTR+gdClrOCU1bjBAlijTZiJXHE5IztICKC5QOP9Zdhe1f1+NaH8cwu+t\nWtobFrOtcIw1P0krAW3jF+xZNYbPk2Q1lwbGevplYQKBgAYvv9vju9A6zCxkwXLp\nOe502nrYADopCoXSzhPzlkHQNFBwbCFGOZBV9NL4+cngrO7Wv9PKMlipHGC/54ON\n0It7YH1D/qcwuiXMd5pkYg8KrLwrzFTt7cXGuDa1n87NP8EfI1R7aaRFfhuqjfBr\ngEGybnfNOky02v+jB562/h5b\n-----END PRIVATE KEY-----\n",
                    "client_email": "firebase-adminsdk-ex9x9@alabaster-a518d.iam.gserviceaccount.com",
                    "client_id": "117775916468727604824",
                    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
                    "token_uri": "https://oauth2.googleapis.com/token",
                    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
                    "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-ex9x9%40alabaster-a518d.iam.gserviceaccount.com"
                  ');
                $messaging = $factor->createMessaging();
                $message = CloudMessage::withTarget('token', $device_id)
                    ->withNotification(Notification::create($title, $message));
                if ($data) {
                    $message->withData($data);
                }
                $messaging->send($message);
            }
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
//        return $interval;
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

//        $formattedDate = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
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
//        $stripeCustomer = $this->stripe->customers->retrieve($stripe_customer_id);

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
