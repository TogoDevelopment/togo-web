<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once '../includes/Services/testService.php';

$TOGO_Test = new TOGO_Test();

$TOGO_Test->testFunc();

