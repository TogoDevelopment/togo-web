<?php

class Firebase
{

    public function sendNotification($TokenArrays, $data)
    {
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(
            'registration_ids' => $TokenArrays,
            'data' => $data,
            'priority' => 'high'
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here, for android
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }

    public function sendIOSNotification($TokenArrays, $data)
    {
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(
            'registration_ids' => $TokenArrays,
            'data' => $data,
            'priority' => 'high',
            'mutable_content' => true,
            'content_available' => true
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAyKrODbE:APA91bGsGnxwhOsuWfhHcQwxn8cmoRmrQ_hiRE7B3xgXUbuYPtumGZ1DcwyL0xc4lUa31t4DILNxLIywooGqWNfOurk4ulEB4XduleamCtsdflsEbJsapptEANOVFvG8GSh80AUQnik8'; // key here, for android
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }

    public function sendWebNotification($TokenArrays, $data, $title, $body/* , $image */)
    {
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        // $image = 'https://togo.ps/togo/MobileAPi/' . $image;

        $arrayToSend = array(
            "notification" => [
                "body"  => $body,
                "title" => $title,
                // "image" => $image
            ],
            'registration_ids' => $TokenArrays,
            'data' => $data,
            'priority' => 'high',
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAyKrODbE:APA91bGsGnxwhOsuWfhHcQwxn8cmoRmrQ_hiRE7B3xgXUbuYPtumGZ1DcwyL0xc4lUa31t4DILNxLIywooGqWNfOurk4ulEB4XduleamCtsdflsEbJsapptEANOVFvG8GSh80AUQnik8'; // key here, for web
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        
        //Close request
        curl_close($ch);
        return $response;
    }

    public function sendTokenNotification($TokenArrays, $Title, $MessageData, $IdOrder, $intent)
    {
        $notification = array('title' => $Title, 'body' => $MessageData, 'IdOrder' => $IdOrder, 'TypeIntent' => $intent, 'sound' => "default");

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(
            'registration_ids' => $TokenArrays,
            'data' => $notification,
            'TypeIntent' => $intent,
            'priority' => 'high'
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }

    public function sendToTransporterBidEngin($TokenArrays, $Title, $MessageData, $IdOrder)
    {
        $notification = array('title' => $Title, 'body' => $MessageData, 'IdOrder' => $IdOrder, 'TypeIntent' => 'NewOrderDetails', 'sound' => "default");

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(

            'registration_ids' => $TokenArrays,
            'data' => $notification,
            'TypeIntent' => 'NewOrderDetails',
            'priority' => 'high'
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }


    public function NotifyClientCostTransporter($TokenArrays, $Title, $MessageData, $IdOrder)
    {
        $notification = array('title' => $Title, 'body' => $MessageData, 'IdOrder' => $IdOrder, 'TypeIntent' => 'CostOffersOrder', 'sound' => "default");

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(

            'registration_ids' => $TokenArrays,
            'data' => $notification,
            'notification' => $notification,
            'priority' => 'high'
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }


    public function sendTransporterNotifyAcceptedOrder($TokenArrays, $Title, $MessageData, $IdOrder)
    {
        $notification = array('title' => $Title, 'body' => $MessageData, 'IdOrder' => $IdOrder, 'TypeIntent' => 'OrderCurrentDetails', 'sound' => "default");

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(

            'registration_ids' => $TokenArrays,
            'data' => $notification,
            'priority' => 'high'
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }

    public function NotificationTranspoDeleteOrder($TokenArrays, $Title, $MessageData, $IdOrder)
    {
        $notification = array('title' => $Title, 'body' => $MessageData, 'IdOrder' => $IdOrder, 'TypeIntent' => 'ClientDeleteOrder', 'sound' => "default");

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(

            'registration_ids' => $TokenArrays,
            'data' => $notification,
            'notification' => $notification,
            'priority' => 'high'
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }

    public function NotificationClinetDeleteOrder($TokenArrays, $Title, $MessageData, $IdOrder)
    {
        $notification = array('title' => $Title, 'body' => $MessageData, 'IdOrder' => $IdOrder, 'TypeIntent' => 'TransporterDeleteOrder', 'sound' => "default");

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");

        $arrayToSend = array(

            'registration_ids' => $TokenArrays,
            'data' => $notification,
            'notification' => $notification,
            'priority' => 'high'
        );
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
        return $response;
    }
}
