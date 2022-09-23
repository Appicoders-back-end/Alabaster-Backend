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
                $factor = (new Factory())->withServiceAccount('{
                              "type": "service_account",
                              "project_id": "alabaster-73af0",
                              "private_key_id": "84e3a695d35144b65871de5ae8887adbf276a615",
                              "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC86yLTq80rtcsd\nXcjRWF67ud5cFWP8tKlvfKWd9Dm0aqF3uz/rrMF/fo2WB5NpWAlaTZ/YcgiVCTBc\nTTF2deQllqlHtr/2fRfKKDgiOU/L2krXJ3eeZrfbvpJ5MEknY09y9+JyTxXoovyC\nks3QB1vEMKwHPR5yqEIvsH3qfr1SEp8KVhcIj7hmiyPgUxVPo23a2UX5Vdm1D8iU\nxpf+ZsrL/5836xvdNuqhQTaAsOOq8JANjgmAQ8WSTv0DmNnNbTqGGvS7kBmxEkkj\nR37RCTARYrpNXz0+LCC+jzNgTJS6yJXE9lBOnB8hYwdDR9oYx0lnnxAm1qvTm5b/\nhOZJOWwdAgMBAAECggEABjWCy14T/0BS/AGM1XQrOueSa0h1scTJsjhKMWPC1yi1\n0VuJYu7M3KqbyMcYQ6fva/UgqfDt6/rYrzPtsMte05OjasMfuIam46lIo2rqsgdY\nGqdGRNTyubY5lkhgvLqeGugSqUSecpF5+jWmerrL+8NiWz0mQfwnOFRVGYIPj8Hh\njzghXFNVyztwBjr1EhLxu1XsD0qlEhpdPjHYYnCwxKyl4G6rWM5cD4QKxN/yIm9I\nBtPD7y2uwi8yhS3PZyJMMz/xDH6qmu++QGYYR/1H9M3KQb7RqHzgTFq2VC+Dc/rr\ngqbr304C5sfb/LKvhyvB2TzwLupj+AwKkD9C8KRD2QKBgQDhOZGfH1TjzyQiVAFE\nXvba8MP9JbDZsCNkNiiER4lfBKktqBwhSJv6G83TAC/rPYf8kuiQZ8afvywH1Nrq\njTnxdGwT6eegXRTVdBXi/4ilmODU9bTPi6jkrQCFIVlO70V4U2BPinu5ubQFKBnr\n5bshLpo+DAuVxYfWhrHqZfaZlQKBgQDWu49EVzovs2r4DkMUF+lAtpQTYjHCofs8\nsijYLWwTzZZbFtwezao+4IOt124Q5S9go65HTz3fcF1IlVxOZeHSE0ng4UkGPOvS\nWzzRmDVVDgRYjYOlmShVMjLaI1L9sEZOSfyg2A9UDDbb9b6U2Jv4kFo4B/t0Xvpk\nxgbt8jM2aQKBgQDNf66L5pWZKoZIwCdLz+4jBG1/DCXZ6inQpM3BLFh+Fw2Z9/p6\nr/qJcVcSf+g5FrJ+VTjXkaicV10AZEm+m09ULSrz3IcYPfXlcP8LWKbAmcYC6ZGp\nkT9wLx37WttW92CGkmSdDrknU/aageVs5PciJphbprfnVw1DQzEQsyoXJQKBgQDH\ndSLnvQpMQqEK1tyR7n/4X199/cjw3FstDuQHoXFxl5ag98PxTOJlU1CdCq2vPeNb\nJc79Z+q2AH51rF84Z0RySP5nx3t4MuBt3dfJbFOltMZupsxw5qnjMSSxIPy2rqUv\nlWP215qtXEcc29ByHB4MrbFPuIJmns6BCrxnC4FseQKBgFYhqHX5WrvL1/X1/aA4\ntNCiA1z1shoomxv5HVVGOFyVQjRaetdhbfaK2gTnii75swuqRZKP9UKd9d0vXXK4\nItRKPbCxlaCl5dEBptZkgIpEoKj8/tq2IeYskToJ7tBYTeUvGwB3nhiXpXKq6I4L\nTGWoiDOa1TGEOinEpyFmeWwD\n-----END PRIVATE KEY-----\n",
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
