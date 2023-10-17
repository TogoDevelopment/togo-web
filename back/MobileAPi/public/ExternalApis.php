<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once '../includes/database.php';
require_once '../includes/Apis.php';

$TOGOApp->setDatabase($database);

$tempActions = array();

if (isset($_POST)) {

    if (false/* $_POST['isTest'] == "test" */) {

        $myfile = fopen("postDataTest.log", "r") or die("Unable to open file!");

        $tempContent = "";
        $tempArrHolder = array();

        while (!feof($myfile)) {
            $tempLine = fgets($myfile);

            if ($tempLine == " ================ \n") {

                $parseArr = eval("return " . $tempContent . ";");

                array_push($tempArrHolder, $parseArr);

                $tempContent = "";
            } else {

                $tempContent .= $tempLine;
            }
        }

        fclose($myfile);

        ///////////////////////////////////////////

        $doubled = 0;

        for ($i = 0; $i < count($tempArrHolder); $i++) {
            /* if ((($tempArrHolder[$i]['is_delivered'] == "1" && $_POST['is_delivered'] == "1") || ($tempArrHolder[$i]['action_type'] == "shipment_status_delivered" && $_POST['action_type'] == "shipment_status_delivered")) && $tempArrHolder[$i]['tracking_id'] == $_POST['tracking_id']) {
                $doubled = 1;
            } */

            /* if ((($tempArrHolder[$i]['is_delivered'] == "1" || $tempArrHolder[$i]['action_type'] == "shipment_status_delivered") && ($_POST['is_delivered'] == "1" || $_POST['action_type'] == "shipment_status_delivered")) && $tempArrHolder[$i]['tracking_id'] == $_POST['tracking_id']) {
                $doubled = 1;
            } */

            /* if ($tempArrHolder[$i]['is_delivered'] == "1" && $_POST['is_delivered'] == "1" && $tempArrHolder[$i]['tracking_id'] == $_POST['tracking_id']) {
                $doubled = 1;
            } */

            /* if ($tempArrHolder[$i] == $_POST) {
                $doubled = 1;
                break;
            } */

            if ($tempArrHolder[$i]['is_delivered'] == $_POST['is_delivered'] && $tempArrHolder[$i]['action_type'] == $_POST['action_type'] && $tempArrHolder[$i]['tracking_id'] == $_POST['tracking_id']) {
                $doubled = 1;
            }
        }

        $_POST['time_stamp'] = date("Y-m-d H:i:s");
        file_put_contents("postDataTest.log", var_export($_POST, true) . "\n ================ \n", FILE_APPEND);

        if ($doubled == 0) {
            // don't call
        } else {
            // call
        }
    } else if (false) {
        /* file_put_contents("postData.log", var_export($_POST, true) . "\n ================ \n", FILE_APPEND);
        file_put_contents("postDataAll.log", var_export($tempActions, true));

        $doubled = 0;

        for ($i = 0; $i < count($tempActions); $i++) {
            if ($_POST == $tempActions[$i]) {
                $doubled = 1;
            }
        }

        if ($doubled == 0) {
            array_push($tempActions, $_POST);

            $TOGOApp->albarqCallback($_POST);
        } */

        $logfile = fopen("postData.log", "r") or die("Unable to open file!");

        $tempContent = "";
        $tempArrHolder = array();

        while (!feof($logfile)) {
            $tempLine = fgets($logfile);

            if ($tempLine == " ================ \n") {

                $parseArr = eval("return " . $tempContent . ";");

                array_push($tempArrHolder, $parseArr);

                $tempContent = "";
            } else {

                $tempContent .= $tempLine;
            }
        }

        fclose($logfile);

        ///////////////////////////////////////////

        $doubled = 0;

        for ($i = 0; $i < count($tempArrHolder); $i++) {

            if ($tempArrHolder[$i]['is_delivered'] == $_POST['is_delivered'] && $tempArrHolder[$i]['action_type'] == $_POST['action_type'] && $tempArrHolder[$i]['tracking_id'] == $_POST['tracking_id']) {
                $doubled = 1;
                break;
            }
        }

        file_put_contents("postData.log", var_export($_POST, true) . "\n ================ \n", FILE_APPEND);

        if ($doubled == 0) {
            $TOGOApp->albarqCallback($_POST);
        }
    } else {

        $randNum = rand(1, 10);
        sleep($randNum);
        
        $logfile = fopen("albarqCallbackData.log", "r") or die("Unable to open file!");

        $tempContent = "";
        $tempArrHolder = array();

        while (!feof($logfile)) {
            $tempLine = fgets($logfile);

            if ($tempLine == " ================ \n") {

                $parseArr = eval("return " . $tempContent . ";");

                array_push($tempArrHolder, $parseArr);

                $tempContent = "";
            } else {

                $tempContent .= $tempLine;
            }
        }

        fclose($logfile);

        file_put_contents("albarqCallbackData.log", var_export($_POST, true) . "\n ================ \n", FILE_APPEND);
        $TOGOApp->addBarqLog($_POST);

        ///////////////////////////////////////////

        $is_new_reveresed = $_POST['is_reversed_shipment'] == "1" ? "1" : "0";

        if ($is_new_reveresed == "0") {
            $new_tracking_id = $_POST['tracking_id'];
        } else if ($is_new_reveresed == "1") {
            $new_tracking_id = $_POST['base_shipment_tracking_id'];
        }

        $new_action_type = $_POST['action_type'];
        $is_new_delivered = $_POST['is_delivered'] == "1" ? "1" : "0";
        $togo_order_id = $_POST['invoice_ref'];

        if ($new_action_type == "shipment_status_information_received") {
            // add new al-barq id
            $TOGOApp->albarqAddNewOrderCallback($togo_order_id, $new_tracking_id);
        } else if ($new_action_type == "shipment_status_returned_to_shipper") {
            // return
            $TOGOApp->albarqReverseOrderCallback($new_tracking_id);
        } else if ($is_new_delivered == "1") {

            $doubled = 0;

            for ($i = 0; $i < count($tempArrHolder); $i++) {

                if ($is_new_reveresed == "0") {
                    $old_tracking_id = $tempArrHolder[$i]['tracking_id'];
                } else if ($is_new_reveresed == "1") {
                    $old_tracking_id = $tempArrHolder[$i]['base_shipment_tracking_id'];
                }
                
                $old_action_type = $tempArrHolder[$i]['action_type'];
                $is_old_delivered = $tempArrHolder[$i]['is_delivered'] == "1" ? "1" : "0";

                if ($old_tracking_id == $new_tracking_id && $is_old_delivered == "1" && $old_action_type != "shipment_status_returned_to_shipper") {
                    $doubled = 1;
                    break;
                }
            }

            if ($doubled == 0) {
                // deliver
                $TOGOApp->albarqFinishOrderCallback($new_tracking_id);
            }
        } else if ($new_action_type == "shipment_status_deleted") {
            // delete
            $TOGOApp->albarqDeleteOrderCallback($new_tracking_id);
        } else if ($new_action_type == "shipment_status_with_courier") {
            // pickup
            $TOGOApp->albarqPickupOrderCallback($new_tracking_id);
        } else {
            // record only
            $TOGOApp->albarqRecordActionCallback($new_tracking_id, $new_action_type);
        }
    }
}

/* if (isset($_POST)) {
    if ($_POST['action_type'] == 'shipment_status_delivered' || $_POST['is_delivered'] == '1') {
        $isDelivered = $TOGOApp->albarqCheckFinishedOrder($_POST['invoice_ref']);

        if ($isDelivered != "1") {
            $TOGOApp->albarqCallback($_POST);
        }
    } else {
        $TOGOApp->albarqCallback($_POST);
    }
} */