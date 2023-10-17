<?php

require_once 'database.php';


/*$query_check_Date="Select Distinct WorkTime.SatTimeStart As TSatStart, WorkTime.SatTimeFinish As TSatEnd,
            WorkTime.SunTimeStart As TSunStart ,WorkTime.SunTimeFinish As TSunEnd,
            WorkTime.MonTimeStart As TMonStart ,WorkTime.MonTimeFinish As TMonEnd,
            WorkTime.TueTimeStart As TTusStart ,WorkTime.TueTimeFinish As TTusEnd,
            WorkTime.WenTimeStart As TWenStart ,WorkTime.WenTimeFinish As TWenEnd,
            WorkTime.ThuTimeStart As TThuStart ,WorkTime.ThuTimeFinish As TThuEnd,
            WorkTime.FriTimeStart As TFriStart ,WorkTime.FriTimeFinish As TFriEnd
            From
            WorkDaysTime As WorkTime,TransporterWorkCity As WorkCity,TransporterCarInfo As TransporterCar,
            OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
            Where 
            TransporterCustomer.IsVerified=1 
            AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
            AND TransporterCustomer.IsAccepted=1 AND TransporterCustomer.id=WorkTime.CustomerId AND TransporterCustomer.id='AF9C18E9-077D-4ACA-AADE-1043D2CFEA21'
            ";
            */
$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);


$query_check_Date = "Select Distinct WorkTime.SatTimeStart As TSatStart, WorkTime.SatTimeFinish As TSatEnd,
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
                TransporterCustomer.IsVerified=1 
                AND WorkTime.CustomerId='756EDF44-2CDF-46D8-8C92-FA8BEA1BE58C'
                AND WorkTime.CustomerId=TransporterCustomer.id
                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
                AND TransporterCustomer.IsAccepted=1 
                ";


date_default_timezone_set('Asia/Jerusalem');
$Date = date('H:i:s');

$result = $database->query($query_check_Date);

if ($result == true)
    echo "true78";
else
    echo "fallse";

$row = $database->fetchArray($result);
echo "kkll" . $row['TSunStart']->format('H:i:s');

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
    $query4 = "select * from WorkDaysTime where id='756EDF44-2CDF-46D8-8C92-FA8BEA1BE58C' AND ('$Date' between '$end_time' AND '$star_time')";
} else {

    $flag = 0;
    $query4 = "select * from WorkDaysTime where id='756EDF44-2CDF-46D8-8C92-FA8BEA1BE58C' AND ('$Date' between '$star_time' AND '$end_time')";
}

/*
 $query_Get_Transporter_Time="Select Distinct WorkTime.SatTimeStart As TSatStart, WorkTime.SatTimeFinish As TSatEnd,
                WorkTime.SunTimeStart As TSunStart ,WorkTime.SunTimeFinish As TSunEnd,
                WorkTime.MonTimeStart As TMonStart ,WorkTime.MonTimeFinish As TMonEnd,
                WorkTime.TueTimeStart As TTusStart ,WorkTime.TueTimeFinish As TTusEnd,
                WorkTime.WenTimeStart As TWenStart ,WorkTime.WenTimeFinish As TWenEnd,
                WorkTime.ThuTimeStart As TThuStart ,WorkTime.ThuTimeFinish As TThuEnd,
                WorkTime.FriTimeStart As TFriStart ,WorkTime.FriTimeFinish As TFriEnd,
                TransporterCustomer.id As CustomerId,TransporterCustomer.LanguageId As LangCustmer,
                OrderBid.createdAt As OrderDate,OrderBid.id As OrderID,
                OrderBid.deliveryWay As DeliveryTiype,OrderAddress.IdCity As SourceCityId
                ,OrderAddress.IdCityDes As DestinationCityId
                From
                WorkDaysTime As WorkTime,TransporterWorkCity As WorkCity,TransporterCarInfo As TransporterCar,
                OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
                Where 
                OrderAddress.IdOrderBidEngin=OrderBid.id AND TransporterCustomer.IsVerified=1 
                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
                AND TransporterCustomer.IsAccepted=1 
                AND (OrderAddress.IdCity=WorkCity.CityId OR OrderAddress.IdCityDes=WorkCity.CityId)
                AND OrderBid.Idvehicle=TransporterCar.CarImgId
                
                ";*/

$query_Get_Transporter_Time = "Select Distinct
                TransporterCustomer.id As CustomerId,TransporterCustomer.LanguageId As LangCustmer,
                OrderBid.createdAt As OrderDate,OrderBid.id As OrderID,
                OrderBid.deliveryWay As DeliveryTiype,OrderAddress.IdCity As SourceCityId
                ,OrderAddress.IdCityDes As DestinationCityId
                From
                WorkDaysTime As WorkTime,TransporterWorkCity As WorkCity,TransporterCarInfo As TransporterCar,
                OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
                Where 
                OrderAddress.IdOrderBidEngin=OrderBid.id AND TransporterCustomer.IsVerified=1 
                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
                AND TransporterCustomer.IsAccepted=1 
                AND (OrderAddress.IdCity=WorkCity.CityId OR OrderAddress.IdCityDes=WorkCity.CityId)
                AND OrderBid.Idvehicle=TransporterCar.CarImgId 
                AND TransporterCustomer.id='756EDF44-2CDF-46D8-8C92-FA8BEA1BE58C'
                AND WorkCity.CustomerId=TransporterCustomer.id AND OrderBid.createdAt > OrderBid.DateLoad
                
                
                ";

$OrderArray = array();
$result_CheckTime = sqlsrv_query($database->getConnect(), $query4, $params, $options);
echo '<br>';
echo $star_time . '<br>';
echo $end_time . '<br>';

$row_count = sqlsrv_num_rows($result_CheckTime);
if ($flag == 0) {
    if ($row_count > 0) {

        $result_get_Order = $database->query($query_Get_Transporter_Time);

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


            array_push($OrderArray, array("idOrder" => $row['OrderID'], "FromCity" => $row_CitySource['CityName'], "ToCity" => $row_CityDes['CityName']));

        }

        echo json_encode(array("server_response" => $OrderArray));
        //$Status="Open";
        // echo $Status.'<br>'; 
    } else {
        $Status = "Close";

        echo $Status . '<br>';
    }

} else {
    if ($flag == 1) {
        if ($row_count > 0) {

            $Status = "Close";

            echo $Status . '<br>';
        } else {
            //$Status="Open";
            //	echo $Status.'<br>'; 

            $result_get_Order = $database->query($query_Get_Transporter_Time);

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


                array_push($OrderArray, array("idOrder" => $row['OrderID'], "FromCity" => $row_CitySource['CityName'], "ToCity" => $row_CityDes['CityName']));

            }

            echo json_encode(array("server_response" => $OrderArray));

        }
    }
}


/*
    
  $query_Get_Transporter_Time="Select Distinct WorkTime.SatTimeStart As TSatStart, WorkTime.SatTimeFinish As TSatEnd,
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
                OrderAddress.IdOrderBidEngin=OrderBid.id AND TransporterCustomer.IsVerified=1 
                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
                AND TransporterCustomer.IsAccepted=1 
                AND (OrderAddress.IdCity=WorkCity.CityId OR OrderAddress.IdCityDes=WorkCity.CityId)
                AND OrderBid.Idvehicle=TransporterCar.CarImgId
                
                ";*/


echo "yy";

/* $query_Get_Transporter_Time="Select Distinct
 WorkCity.CityId As CityTransporter,TransporterCustomer.id As CustomerId
 From
 TransporterWorkCity As WorkCity,
 OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
 Where 
 OrderAddress.IdOrderBidEngin=OrderBid.id AND TransporterCustomer.IsVerified=1 
 AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
 AND TransporterCustomer.IsAccepted=1 
 AND (OrderAddress.IdCity=WorkCity.CityId OR OrderAddress.IdCityDes=WorkCity.CityId) 
 
 ";*/


/* $result=$database->query($query_Get_Transporter_Time);   
 if($result == true)
 echo "true";
 else
 echo "fallse";
 while($row= $database->fetchArray($result))*/
// echo $row['CustomerId']."<br>";


$query_Get_Transporter_Time = "Select Distinct
                TransporterCustomer.id As CustomerId,TransporterCustomer.LanguageId As LangCustmer,
                OrderBid.createdAt As OrderDate,OrderBid.id As OrderID,
                OrderBid.deliveryWay As DeliveryTiype,OrderAddress.IdCity As SourceCityId
                ,OrderAddress.IdCityDes As DestinationCityId
                From
                WorkDaysTime As WorkTime,TransporterWorkCity As WorkCity,TransporterCarInfo As TransporterCar,
                OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
                Where 
                OrderAddress.IdOrderBidEngin=OrderBid.id AND TransporterCustomer.IsVerified=1 
                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL )
                AND TransporterCustomer.IsAccepted=1 
                AND (OrderAddress.IdCity=WorkCity.CityId OR OrderAddress.IdCityDes=WorkCity.CityId)
                AND OrderBid.Idvehicle=TransporterCar.CarImgId 
                AND TransporterCustomer.id='756EDF44-2CDF-46D8-8C92-FA8BEA1BE58C'
                AND WorkCity.CustomerId=TransporterCustomer.id
                
                ";


/*$result_CitySource=$database->query($query_Get_Transporter_Time);
                        while($row_CitySource= $database->fetchArray($result_CitySource))
                        {
                            echo $row_CitySource['OrderID']."<br>";
                        }*/

?>