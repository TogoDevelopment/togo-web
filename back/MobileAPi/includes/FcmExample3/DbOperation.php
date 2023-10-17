<?php
require_once '../database.php';

class DbOperation
{
    //Database connection link
    private $con;

    //Class constructor
    function __construct()
    {

        $con = $database->getConnect();


    }


    //getting all tokens to send push to all devices
    public function getAllTokens()
    {
        $stmt = $this->con->prepare("SELECT token FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $tokens = array();
        while ($token = $result->fetch_assoc()) {

            if ($token['token'] != null) {
                echo $token['token'];
                array_push($tokens, $token['token']);
            }
        }
        return $tokens;
    }


    //getting a specified token to send push to selected device
    public function getDriverToken($driverNumber)
    {
        echo "lll";

        // $stmt = $conn->prepare("SELECT Token FROM users where MobileNumber='$driverNumber'");
        $query_GiveToken = "SELECT Token FROM Passenger where MobileNumber='0569270194'";
        $result_Get_Token = sqlsrv_query($database->getConnect(), $query_GiveToken, $params, $options);


        $tokens = array();
        while ($token = sqlsrv_fetch_array($result_Get_Token, SQLSRV_FETCH_ASSOC)) {

            if ($token['Token'] != null) {
                echo $token['Token'];
                array_push($tokens, $token['Token']);
            }
        }
        return $tokens;

    }


    //getting all the registered devices from database 
    public function getAllDevices()
    {
        $stmt = $this->con->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

}

