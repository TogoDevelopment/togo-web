<?php

//importing required files 

require_once 'Firebase.php';

require_once 'Push.php';
require_once '../../database.php';
$params = array();
$options = array("Scrollable" => null);

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //hecking the required params 
    if (isset($_POST['title']) and isset($_POST['Message']) and isset($_POST['NumberPassenger'])) {

        //creating a new push
        $push = null;
        //first check if the push has an image with it

        //if the push don't have an image give null in place of image
        $push = new Push(
            $_POST['title'],
            $_POST['Message'],
            null
        );

        $NumberPassenger = $_POST['NumberPassenger'];

        //getting the push from push object
        $mPushNotification = $push->getPush();
        $query_GiveToken = "SELECT * FROM Passenger where MobileNumber='$NumberPassenger'";

        $result_Update_Name = sqlsrv_query($database->getConnect(), $query_GiveToken, $params, $options);

        $tokens = array();
        $token = sqlsrv_fetch_array($result_Update_Name, SQLSRV_FETCH_ASSOC);
        if ($token['Token'] != null) {
            echo $token['Token'];
            array_push($tokens, $token['Token']);
        }
        //getting the token from database object 
        $devicetoken = $tokens;

        // getDriverToken("kk");
        //creating firebase class object 
        $firebase = new Firebase();

        //sending push notification and displaying result 
        echo $firebase->send($devicetoken, $mPushNotification);

        function getDriverToken($driverNumber)
        {


            // $stmt = $conn->prepare("SELECT Token FROM users where MobileNumber='$driverNumber'");
            $query_GiveToken = "SELECT * FROM Passenger where MobileNumber='0569270194'";

            $result_Update_Name = sqlsrv_query($database->getConnect(), $query_GiveToken, $params, $options);

            echo "fg";

            $tokens = array();
            $token = sqlsrv_fetch_array($result_Get_Token, SQLSRV_FETCH_ASSOC);
            if ($token['Token'] != null) {
                echo $token['Token'];
                array_push($tokens, $token['Token']);
                return $tokens;
                /* while($token = sqlsrv_fetch_array( $result_Get_Token, SQLSRV_FETCH_ASSOC)){
                     
                     if($token['Token']!=null){
                         echo $token['Token'];
                     array_push($tokens, $token['Token']);
                     }
                 }
                 return $tokens; */

            }
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Parameters missing';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}

echo json_encode($response);