<?php

if ($json = json_decode(file_get_contents("php://input"), true)) {
    $data = $json;
} else {
    $data = $_POST;
}

if ($data) {

    file_put_contents("lahza_test_log.log", var_export($data, true) . "\n ================ \n", FILE_APPEND);
} else {

    file_put_contents("lahza_test_log.log", var_export("none", true) . "\n ================ \n", FILE_APPEND);
}



http_response_code(200);

return;

$secretKey = 'sk_test_avPn10K5IIonEkb0jhQ5bfmEGIDMgeQTX';

// Retrieve the payload and signature from the request
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_LAHZA_SIGNATURE'];

// Verify signature
$hash = hash_hmac('sha512', $payload, $secretKey);
if ($hash === $signature) {
    // Signature is valid, process the event
    $event = json_decode($payload, true);
    // Do something with the event
    // ...
    file_put_contents("lahza_test_log.log", var_export($event, true) . "\n ================ \n", FILE_APPEND);
}

http_response_code(200);
