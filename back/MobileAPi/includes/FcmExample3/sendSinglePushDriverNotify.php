<?php

//importing required files 

require_once 'Firebase.php';

require_once 'Push.php';
require_once '../../database.php';
$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //hecking the required params 
    if (isset($_POST['title']) && isset($_POST['Message']) && isset($_POST['CrId'])) {

        //creating a new push
        $push = null;
        //first check if the push has an image with it

        //if the push don't have an image give null in place of image
        $push = new Push(
            $_POST['title'],
            $_POST['Message'],
            null
        );

        $CarID = $_POST['CrId'];

        //getting the push from push object
        $mPushNotification = $push->getPush();
        $query_GiveToken = "select * FROM Car where id='3A379B66-58A8-4565-A42F-9EC18FA26784'";

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