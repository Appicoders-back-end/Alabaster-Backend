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
                        "project_id": "alabaster-66547",
                        "private_key_id": "4f798c10df59be194e74c3a5668832ce252f0787",
                        "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCnppRi2eJp1KWS\n1gVPCfXdiAgYPJjUh2IBLorGhan2+8TMa6KAtmP1mJVnLxRjV2pMc5jtFXouYrZt\nMBGBx4g4lcEE2MGcIbRJrEqNyVQMpkCDm85ftX+MrbIFotDu4NIxGZOEdFT5PRVG\ntD2orfG2vG2FC4T16baI+zj9MXeljzj1wN8hqyGVQa5DsF8r1gYRn3lxP8ZfKiBo\n+7iVq1FcfEiPa37fQtRR8HJ8TqVvpDLH9aMn9V17fXcu6/LV8Hbq92u5nmS6Vkeo\nKONy9S6IFk1bbknhiT8ro4s6AxDQRQ7cmWbe12P6UX6mN6MIGwWfrtdolCvVCABK\nG/zujBpTAgMBAAECggEATUltib6hcqFt7SnOC7hvSH/QH4A8AmohI6NVpZx0FODP\nQl/ya7zpiwya/5UEJKHlZrY5zz5B88VdDSwGPhqCsXdUIsxcQQKGrxoGyqOAJE62\nWhXHR3cQBIYEljWPdThenfUI/Rx2Gepvdwdbj3oU/uWR5a5ATu+9zxtlk8+stTz4\n6q94GwrPdtWFyK8zXS8raXDlhMfOhkDkDB9uZN2agfnMVMGM0h2HKbPSv3iwznck\nbH3rG6vjKQvbr8T+QfPGlS6L7Jzr4VZj+fhwPPw8EwuNkqQnzNH46f/0rV+x99pa\nOBI3+QeyBol29axcJqfMrc8kSxlw7RjDgjFsoVwh2QKBgQDWMVb6Qi/weY0dgw84\n8tjK7t2TghIHHkaj7hmh/bza6RqoqFpMEhrvU6PFzpaWlYCeYOmsBiEQVpRSM5Nk\nYqDdgSMFG9MprxWTE10ZjLit/q9LC9d9i5vQexQ5XYtkZTeEn5vac03c7ONGZSU6\nfORzYMYuF3/1Ss2yprR0UMqAawKBgQDIX6d5weoPk/4byyY0kaQVG0oxGGchSPOi\nQyzv5/MCe/h/cwjdQfUf0UBSI4P4Xv8qpEfNlR4EMIPHzLlm5tX4AoSI47vhnDQU\n7IrL7NVCLMz509SDmda55RMF8ixGan/ZP5EcZGwlVylCfAsghlU800lbpN+x+pc7\nw6MyHS8nuQKBgQC6nkp1yO0TzBPTPrkomQ2h4fRT7OPgJ9TMR0s2BGljIe4EPIpP\n9QZatrjeQ1yY3rEtTKrLIpv0LDsWU7F7qSMXWsg0T0Xv74gQmLJo7F6L2nbIIA0k\naVrcjh/Uy1gDpNx2RMn5zrRsZEiwsLd6+g3hKNQCnJ70DLSjjvNy1GFa+QKBgFdr\nrFTp7qWnStlzc5LB7BWtV1w+KliSTIGBz2xIBXUTA2MPnFF0Qm8ES4zKo/xL7gX7\nsLwYEAWB6SzVvBoSIk5XQt26hjEzhmGOpiu8g3qgszlYW0KjbtSiBf+1He4G7wqo\nujXp9mkAMycnmW8yTKQCBuJt947eJvdTLrNhmj45AoGAIbwAUqmNSQGqmph2Lo68\nqu9wu6MmRkI3mx0PqSD77DCu/u82B8fI6uL6gdrTtY+HujGvTgJIQ24y+tIAsjZP\n2+gLnbzseznyhVsXtNYE8aAnqJftO34k9POgFG6N5X/lzk+06CpMe1JYITwErVrh\n+ImExSNPWjKClJf/5eJkdqk=\n-----END PRIVATE KEY-----\n",
                        "client_email": "firebase-adminsdk-rbpub@alabaster-66547.iam.gserviceaccount.com",
                        "client_id": "114432327285873759343",
                        "auth_uri": "https://accounts.google.com/o/oauth2/auth",
                        "token_uri": "https://oauth2.googleapis.com/token",
                        "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
                        "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-rbpub%40alabaster-66547.iam.gserviceaccount.com"
                    }'

                );
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

if (!function_exists('saveImage')) {

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
