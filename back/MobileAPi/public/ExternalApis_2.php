<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once '../includes/database.php';
require_once '../includes/Apis.php';

$TOGOApp->setDatabase($database);

if ($json = json_decode(file_get_contents("php://input"), true)) {
    // print_r($json);
    $data = $json;
} else {
    // print_r($_POST);
    $data = $_POST;
}

if ($data) {
    
    file_put_contents("logestechsCallbackData.log", var_export($data, true) . "\n ================ \n", FILE_APPEND);
    $TOGOApp->addLogestechsLog($data);

    $TOGOApp->logestechsCallback($data);
}
