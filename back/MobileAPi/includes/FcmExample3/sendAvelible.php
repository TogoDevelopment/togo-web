<?php
require_once 'DbOperation.php';
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_name = $_POST["username"];
    $name = $_POST["name"];
    $password = $_POST["password"];
    $secret = $_POST["secret"];
    $gender = $_POST["gender"];

    $department = $_POST["department"];

    $token = $_POST['token'];

    $db = new DbOperation();

    $result = $db->registerDevice($user_name, $name, $password, $gender, $secret, $department, '0', $token);

    if ($result == 0) {
        $response['error'] = false;
        $response['message'] = 'Device registered successfully';
    } elseif ($result == 2) {
        $response['error'] = true;
        $response['message'] = 'Device already registered';
    } else {
        $response['error'] = true;
        $response['message'] = 'Device not registered';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid Request...';
}

echo json_encode($response);