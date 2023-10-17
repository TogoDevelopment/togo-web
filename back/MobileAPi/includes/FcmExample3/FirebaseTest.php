<?php
$notification = array('title' => 'hhh', 'body' => 'uuu', 'IdOrder' => 'tttt', 'TypeIntent' => 'NewOrderDetails', 'sound' => "default");

$ch = curl_init("https://fcm.googleapis.com/fcm/send");


$TokenArrays = array();

array_push($tokens, $Token_Customer);

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


?>
