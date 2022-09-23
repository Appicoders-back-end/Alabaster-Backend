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
                $factor = (new Factory())->withServiceAccount(
                    '{
                      "type": "service_account",
                      "project_id": "alabaster-73af0",
                      "private_key_id": "0cc6e75b8fa36e08088bd41c83da9c098ce1a007",
                      "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC9c5sIqRrU88yM\nNVZdMehcvZaI+GDwTnznp9Cp7EU0hSAhtV1iU3j9sbKFQOv1N23ZOjprjUQmluMM\nuYMCR58blRxpsiz9QN0/ni+HqHVL3Bj2QHcBI+677U4mRr4YQ+dHd92zh4Abw6GX\nIwO4q4g1amt1x1u+F1IyZYz3FdTUKip1dZeSGOrbnYeb6RZcKR8P0+nbmz6TnNKb\nTFAg6/DEdMPr5vZTM2ivE4HYcUejMebsSjwjYZTe+eHKNk4JN9Nsy8n0m9tquhQP\nOx/+OgWBhdqb7y3GrSq8ayVQ5bsLGP5hnpml+KVMutk+7DrgsYc9NgOSLIuAnRtM\nPs8GqxYbAgMBAAECggEAGEPYVZGjOCdJyaODPkiIJGi8ASse72h+IwWhekIi0wlC\nBk8+eLuMQaIi5WJdVnkU/r+wf9oGIpMs3ZJdL0uRG21LyFXj4UQPknhmSg/PhjnY\nkKHNHD5H85X/bnOdIWqBEuxNXxM521SBO+RmM570wTKyNNGl9ID0LDRJi9QmAE51\ny5ZqlFIe1snTt/JSZkVZ7TgHyGhoGbV3L4cqeWLqhOv/pyYuDqbc76NwKcDwMpSX\njWz+xJyFXIh+6OVlPtcg4mON0xJUlvz36FdBw37ge4l8JfvS5BSJkvZpaY8oLvlJ\ntVZ9QjVjax9Ua5Z09zYaEj83rtGhqhOnO4uOV6r68QKBgQDm4KkoqKO1H9Btb0Qr\nwZ0mv3ois2uYY6QZmZDI0UUHrLH/c85dH81GDPEbvyObCuEppiTEP9sJgQg9G20U\nbAAAGMjlr5JYnyc1V+5pRm2V6zmhm/ezU/TLklrHhKeV7gWSt0dy8NixYLQc7A2e\nJNSRoSr1UvUz8puGtTt6CsRoLwKBgQDSEPrYmG8TLqLgqYBuJ2TdlIukYDxSIaaY\nSFrpMVsI0M6Fq1Z4+DRvp+rmCDDitouYrQeiqK+oEFyGcoNCJXi7zGdoEBaDbQsg\nbgINL/DpgWCLPRXjfG+dNmKzEjAQEOSODEs9IfucUPOoaTp0jDFWtFddHRRQywXf\nB4bIG2VJ1QKBgCB3d1JAZMUTtDuvaea9U1wfkQ1QLdFAMdNnxPR4eTybDGzf3CU8\nU6GGMGG2f0tOPFufAYyXbjXn70Comq5EgbuBwL6L8giEWP8nXl9vh/mNGo2fYTXW\nmJYH/rwP64Ep956qJ2ICfHZ1It7uUvvqMfpIr8HP6Ktlcnl/At4mpXgBAoGAeU4O\n3QbhlY0nx156aKHdEEuYe/qKus7t2iBmRyUWMbgKmov/qmmCNjwcXGu8dx7869R3\nhUmt7fpMw+Law1bKKoB18lTf+1L7yuVbz+uwTddFPgKvYidYeMuQIWJOWOi4YwLc\na9f06SaiHc1uUSEn2K7ZiE6jjag/orRA88tGKWUCgYEAlGLqI3A1k+a27fw1noh5\nGnjHwlLaqTVuV9V3JBNNK4QJ6tlg6b6vOjtxQsyJIHZdzCqETGL9Y1HIe9mOMUL0\n39WsFqeVJ8RmlcWzNWsADL5aiGpbCihrIv/Fl7jfRvzC5yD2lHqepDw0lzv3seuo\n26QmYHUP9NsehPQ2Bstvids=\n-----END PRIVATE KEY-----\n",
                      "client_email": "firebase-adminsdk-1i2lu@alabaster-73af0.iam.gserviceaccount.com",
                      "client_id": "109916481288682373386",
                      "auth_uri": "https://accounts.google.com/o/oauth2/auth",
                      "token_uri": "https://oauth2.googleapis.com/token",
                      "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
                      "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-1i2lu%40alabaster-73af0.iam.gserviceaccount.com"
                    }');
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
