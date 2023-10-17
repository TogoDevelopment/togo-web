<?php

class Firebase
{

    public function send($registration_ids, $message, $Notification)
    {
        $notification = array('title' => 'gfh', 'body' => 'gj', 'sound' => "default");
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $notification,
            'notification' => $notification,
            'priority' => 'high'
        );
        return $this->sendPushNotification($fields);
    }

    /*
    * This function will make the actuall curl request to firebase server
    * and then the message is sent 
    */
    private function sendPushNotification($fields)
    {

        //importing the constant files
        require_once 'Config.php';

        //firebase server url to send the curl request
        $url = 'https://fcm.googleapis.com/fcm/send';

        //building headers for the request
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);

        //and return the result 
        return $result;


    }

    function sendToTransporterBidEngin($TokenArrays, $Title, $MessageData, $IdOrder)
    {


        $notification = array('title' => $Title, 'body' => $MessageData, 'sound' => "default");
        // $mPushNotification = $push;
        $firebase = new Firebase();
        echo $firebase->send($TokenArrays, $mPushNotification, $notification);


    }
}

//$Firebase = new Firebase();
$tokens = array();
array_push($tokens, 'c6Z_sar8tZg:APA91bHaN_lcr6uMpDlnWkcIfVEwCG1Bbcg6i6rHWROUZDitsVQ7VOeEHr0eomhTBD6X-8ZMCRTDaCuMFUEPEyvLU4v2V9ehG2F4URb5IYSMP6kByLSwgKtdHu5Sycxd_2jZmqTKo8JN‏');
//$Firebase-> sendToTransporterBidEngin($tokens,'New Order ','Test','Test');


$notification = array('title' => 'gfh', 'body' => 'gj', 'sound' => "default");
$data = array('title' => 'gfh');
//$arrayToSend = array('to' => $token,'notification'=> $notification, 'data' => $arr,'priority'=>'high');


$fields = array(
    'registration_ids' => $tokens,
    'data' => $data,
    'notification' => $notification,
    'priority' => 'high'
);

//firebase server url to send the curl request
$url = 'https://fcm.googleapis.com/fcm/send';

//building headers for the request
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: key= AAAAVxG6vy8:APA91bFZe7-MEkCVjn2EUA3IqsZ0S6RojHlIklyTmQto4Ioh_88rA8OEyT_UyjDFno0_eEXBosLSv8SlvyOmlKuytvzXKLMVW2S7Tw68kHpLih1tNOyqtY4_TMnMki20RlwSrw-EBG76'; // key here

//Initializing curl to open a connection
$ch = curl_init();

//Setting the curl url
curl_setopt($ch, CURLOPT_URL, $url);

//setting the method as post
curl_setopt($ch, CURLOPT_POST, true);

//adding headers 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//disabling ssl support
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

//adding the fields in json format 
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

//finally executing the curl request 
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Curl failed: ' . curl_error($ch));
}

//Now close the connection
curl_close($ch);

//and return the result 
echo $result;

return $result;