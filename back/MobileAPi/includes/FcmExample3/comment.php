<?php
//importing required files 
require_once 'DbOperation.php';
require_once 'Firebase.php';
require_once 'Push.php';

$db = new DbOperation();

$response = array();
$user_add;
$namebook;
$comment;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //hecking the required params 
    if (isset($_POST['title']) and isset($_POST['message']) and isset($_POST['namebook']) and isset($_POST['useradd'])) {
        $namebook = $_POST['namebook'];
        $user_add = $_POST['useradd'];
        $comment = $_POST['comment'];
        //creating a new push
        $push = null;
        //first check if the push has an image with it
        if (isset($_POST['image'])) {
            $push = new Push(
                $_POST['title'],
                $_POST['message'],
                $_POST['image']
            );
        } else {
            //if the push don't have an image give null in place of image
            $push = new Push(
                $_POST['title'],
                $_POST['message'],
                null

            );
        }


        //getting the push from push object
        $mPushNotification = $push->getPush();

        //getting the token from database object 
        $devicetoken = $db->getTokenByuseradd($_POST['useradd'], $_POST['namebook']);

        //creating firebase class object 
        $firebase = new Firebase();

        //sending push notification and displaying result 
        echo $firebase->send($devicetoken, $mPushNotification);
    } else {
        $response['error'] = true;
        $response['message'] = 'Parameters missing';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}

echo json_encode($response);