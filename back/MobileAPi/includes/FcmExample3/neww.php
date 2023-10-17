<?php
//importing required files 

require_once 'DbOperation.php';
require_once 'Firebase.php';
require_once 'Push.php';

require_once '../database.php';


//$db = new DbOperation();
//echo "qqq";


$response = array();
$user_name;

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
        "bvb",
        "rfgrr",
        null
    );
}


//getting the push from push object
$mPushNotification = $push->getPush();

$query_GiveToken = "SELECT * FROM Customer where id='68D42BDB-CCC7-408F-AE7D-46784D26A687‏'";

$result_Update_Name = $database->query($query_GiveToken);

$tokens = array();
$token = sqlsrv_fetch_array($result_Update_Name, SQLSRV_FETCH_ASSOC);
array_push($tokens, 'fUAJ5S3H_IA:APA91bGjSHJ0gc_eedQpyd0qIMgkP3B805nIUTasC2P4Zdq4oeoMQ4HRl2gW_PN4fdntiYsBAxyMySjjik-gaXTATgJm1q3y6Vo1LKXSP4VEl8H2tD8Op2fA-AywnfszDQuN_bQq0w8F');
//array_push($tokens, 'cBdldJ4YArs:APA91bGvL4uTLVmT7R7a7uDjemgcZy9gVverqih3hAGQRaveYprYJK8XL6baRr7_QCpz0fnvFRC7ee7gsiT9j28aWjLIXV804Dt7MTOOZe6WkhD2h7ROVCuDu3AqqznuPPLqyVVEi5OO');

if ($token['Token'] != null) {
    echo $token['Token'];
    array_push($tokens, 'cBdldJ4YArs:APA91bGvL4uTLVmT7R7a7uDjemgcZy9gVverqih3hAGQRaveYprYJK8XL6baRr7_QCpz0fnvFRC7ee7gsiT9j28aWjLIXV804Dt7MTOOZe6WkhD2h7ROVCuDu3AqqznuPPLqyVVEi5OO');
}

// array_push($response, "eOsL7kYsxaU:APA91bEOVU2fPRKfNHgOJBrGtwWT02NQ-abs94IvJ_BjQZKtrrN8C-4oPlfdQiLCdPoSI9TXnk9FT9_drxOPnPebnmYW5Ul-xh9Qh_-7Vos1qXs9GkmdXyC98JzI5vGEFtD1qGfRo_Rn");

//getting the token from database object 
//	$devicetoken = $db->getTokenByEmail("mahmood","علم الحاسوب");

//creating firebase class object 
$firebase = new Firebase();

//sending push notification and displaying result 
echo $firebase->send($tokens, $mPushNotification);


echo json_encode($response);