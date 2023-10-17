<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once '../includes/database.php';
require_once '../includes/Apis.php';

$TOGOApp->setDatabase($database);

if ($json = json_decode(file_get_contents("php://input"), true)) {
    $data = $json;
} else {
    $data = $_POST;
}

if ($data) {

    $response = array("status" => "200");

    /* $data['time'] = date("Y-m-d H:i:s"); */
    
    file_put_contents("oliveryCallbackData.log", var_export($data, true) . "\n ================ \n", FILE_APPEND);

    // $TOGOApp->addOliveryLog($data);

    $TOGOApp->oliveryCallback($data);

    echo json_encode($response);
}
