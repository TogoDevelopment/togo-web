<?php

require_once 'database.php';

$TransporterId = "";

$query_check_Date_Work = "Select Distinct WorkTime.SatTimeStart As TSatStart, WorkTime.SatTimeFinish As TSatEnd,
                WorkTime.SunTimeStart As TSunStart ,WorkTime.SunTimeFinish As TSunEnd,
                WorkTime.MonTimeStart As TMonStart ,WorkTime.MonTimeFinish As TMonEnd,
                WorkTime.TueTimeStart As TTusStart ,WorkTime.TueTimeFinish As TTusEnd,
                WorkTime.WenTimeStart As TWenStart ,WorkTime.WenTimeFinish As TWenEnd,
                WorkTime.ThuTimeStart As TThuStart ,WorkTime.ThuTimeFinish As TThuEnd,
                WorkTime.FriTimeStart As TFriStart ,WorkTime.FriTimeFinish As TFriEnd,
                TransporterCustomer.id As CustomerId
                From
                WorkDaysTime As WorkTime,TransporterWorkCity As WorkCity,TransporterCarInfo As TransporterCar,
                OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
                Where 
                TransporterCustomer.IsVerified=1 AND WorkTime.CustomerId='$TransporterId'
                AND WorkTime.CustomerId=TransporterCustomer.id
                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
                AND TransporterCustomer.IsAccepted=1 
                ";

date_default_timezone_set('Asia/Jerusalem');
$Date = date('H:i:s');

$result_check_Date_Work = $database->query($query_check_Date_Work);

$row = $database->fetchArray($result_check_Date_Work);

if (date('D') === 'Sat') {
    $star_time = $row['TSatStart']->format('H:i:s');
    $end_time = $row['TSatEnd']->format('H:i:s');
} else
    if (date('D') === 'Sun') {

        $star_time = $row['TSunStart']->format('H:i:s');
        $end_time = $row['TSunEnd']->format('H:i:s');

    } else
        if (date('D') === 'Mon') {
            $star_time = $row['TMonStart']->format('H:i:s');
            $end_time = $row['TMonEnd']->format('H:i:s');
        } else
            if (date('D') === 'Tue') {
                $star_time = $row['TTusStart']->format('H:i:s');
                $end_time = $row['TTusEnd']->format('H:i:s');
            } else
                if (date('D') === 'Wed') {
                    $star_time = $row['TWenStart']->format('H:i:s');
                    $end_time = $row['TWenEnd']->format('H:i:s');
                } else
                    if (date('D') === 'Thu') {
                        //echo "Thursday";
                        $star_time = $row['TThuStart']->format('H:i:s');
                        $end_time = $row['TThuEnd']->format('H:i:s');
                    } else
                        if (date('D') === 'Fri') {
                            $star_time = $row['TFriStart']->format('H:i:s');
                            $end_time = $row['TFriEnd']->format('H:i:s');
                        }

$flag = 0;
if ($star_time > $end_time) {

    $flag = 1;
    $query_Check_Status = "select * from WorkDaysTime where CustomerId='$TransporterId' AND ('$Date' between '$end_time' AND '$star_time')";
} else {

    $flag = 0;
    $query_Check_Status = "select * from WorkDaysTime where CustomerId='$TransporterId' AND ('$Date' between '$star_time' AND '$end_time')";
}

$query_Get_Orders = "Select Distinct
                TransporterCustomer.id As CustomerId,TransporterCustomer.LanguageId As LangCustmer,
                OrderBid.createdAt As OrderDate,OrderBid.id As OrderID,
                OrderBid.deliveryWay As DeliveryTiype,OrderAddress.IdCity As SourceCityId
                ,OrderAddress.IdCityDes As DestinationCityId,OrderBid.createdAt As DateOrder,
                OrderBid.DetailsLoad As DetailsOrder,OrderBid.deliveryWay As WaysDeliver
                From
                WorkDaysTime As WorkTime,TransporterWorkCity As WorkCity,TransporterCarInfo As TransporterCar,
                OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
                Where 
                OrderAddress.IdOrderBidEngin=OrderBid.id AND TransporterCustomer.IsVerified=1 
                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
                AND TransporterCustomer.IsAccepted=1 
                AND (OrderAddress.IdCity=WorkCity.CityId Or OrderAddress.IdCityDes=WorkCity.CityId)
                AND OrderBid.Idvehicle=TransporterCar.CarImgId 
                AND TransporterCustomer.id='$TransporterId'
                AND WorkCity.CustomerId=TransporterCustomer.id AND (OrderBid.IsAcceptDelivery=0 OR OrderBid.IsAcceptDelivery IS NULL)
                ";
//AND OrderBid.createdAt > OrderBid.DateLoad
$result_Status = sqlsrv_query($database->getConnect(), $query_Check_Status, $params, $options);
$row_count = sqlsrv_num_rows($result_Status);

$Check_Flag_Work = false;

if ($flag == 0) {
    if ($row_count > 0) {
        $Check_Flag_Work = true;
        //$Status="Open";
    } else {
        $Check_Flag_Work = false;
        //$Status="Close";
    }
} else {
    if ($flag == 1) {
        if ($row_count > 0) {
            $Check_Flag_Work = false;
            //$Status="Close";
        } else {
            $Check_Flag_Work = true;

            //$Status="Open";
        }
    }
}
if ($Check_Flag_Work == true) {
    $result_get_Order = $database->query($query_Get_Orders);

    while ($row = $database->fetchArray($result_get_Order)) {
        $CityIdSource = $row['SourceCityId'];
        $CityIdDestination = $row['DestinationCityId'];
        $CustomerLang = $row['LangCustmer'];

        $query_GetCitySource = "Select CityName From CityRegionLang  Where languageId='$CustomerLang' AND CityId='$CityIdSource'";
        $result_CitySource = $database->query($query_GetCitySource);
        $row_CitySource = $database->fetchArray($result_CitySource);

        $query_GetCityDes = "Select CityName From CityRegionLang  Where languageId='$CustomerLang' AND CityId='$CityIdDestination'";
        $result_CityDes = $database->query($query_GetCityDes);
        $row_CityDes = $database->fetchArray($result_CityDes);

        array_push($OrderArray, array("idOrder" => $row['OrderID'], "DateOrder" => $row['DateOrder']->format('Y-m-d'), "TimeOrder" => $row['DateOrder']->format('H:i:s'), "DeliveryWays" => $row['WaysDeliver'], "DetailsLoad" => $row['DetailsOrder'], "FromCity" => $row_CitySource['CityName'], "ToCity" => $row_CityDes['CityName']));
    }

    echo json_encode(array("server_response" => $OrderArray));
} else {
    echo "NotActiveNow";
}


?>