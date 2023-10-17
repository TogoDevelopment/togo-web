//Forword Order Evrey Customer

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
AND OrderBid.Idvehicle=TransporterCar.CarImgId ";