<?php

require_once 'database.php';

$query_Get_Transporter_Time = "Select Distinct
                                TransporterCustomer.id As CustomerId,TransporterCustomer.LanguageId As LangCustmer,
                                TransporterCustomer.Token As TokenNotify,
                                OrderBid.createdAt As OrderDate,OrderBid.id As OrderID,
                                OrderBid.deliveryWay As DeliveryTiype,OrderAddress.IdCity As SourceCityId
                                ,OrderAddress.IdCityDes As DestinationCityId
                                From
                                TransporterWorkCity As WorkCity,TransporterCarInfo As TransporterCar,
                                OrderBidEngin As OrderBid , OrderBidAddress As OrderAddress ,Customer As TransporterCustomer
                                Where 
                                OrderAddress.IdOrderBidEngin=OrderBid.id AND TransporterCustomer.IsVerified=1 
                                AND TransporterCustomer.IsTransporter=1 AND (TransporterCustomer.IsBlocked=0 Or TransporterCustomer.IsBlocked IS NULL)
                                AND TransporterCustomer.IsAccepted=1 
                                AND (OrderAddress.IdCity=WorkCity.CityId OR OrderAddress.IdCityDes=WorkCity.CityId)
                                AND OrderBid.Idvehicle=TransporterCar.CarImgId AND (OrderBid.Orderfinished = 0 OR OrderBid.Orderfinished IS NULL)
                                AND (OrderBid.IsDeleted = 0 OR OrderBid.IsDeleted IS NULL) AND (OrderBid.IsAcceptDelivery = 0 OR OrderBid.IsAcceptDelivery IS NULL)
                                AND OrderBid.id='9'
                                Order By OrderDate DESC	
                                ";

//$query_Get_Transporter_Time="Select * from Customer";

//CarColorId,LicenceCarNumber

$result_Get_color_Licenc = $database->query($query_Get_Transporter_Time);
while ($row_Details_Car_Transporter = $database->fetchArray($result_Get_color_Licenc))
    echo $row_Details_Car_Transporter['OrderID'] . $row_Details_Car_Transporter['CustomerId'] . '<br>';

if ($result_Get_color_Licenc == true)
    echo "teeest";
else
    echo "rrr";


?>