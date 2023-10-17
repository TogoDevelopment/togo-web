<?php


class OrderService
{

    private $dataBase;

    /**
     * PickupService constructor.
     * @param $dataBase
     */
    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getOrder($orderId)
    {
        $query = "Select orderbidengin.*, orderbidaddress.OtherDetails as fromAddress,
        orderbidaddress.OtherDetailsDes as toAddress, fromCityRegion.name as fromCityName,
		toCityRegion.name as toCityName ,pickupAddress.phone_number As PickupMobile, pickupAddress.name as PickupName
		,receiverAddress.phone_number As ReceiverMobile, receiverAddress.name as ReceiverName 
        from togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
		inner join togo.customer as customer on customer.id = orderbidengin.CustomerId
		left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
		left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
		left outer join togo.addresses as pickupAddress on orderbidaddress.SenderAddressId = pickupAddress.id
		left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        where orderbidengin.id='$orderId' and fromCityRegion.languageId = customer.LanguageId and toCityRegion.languageId = customer.LanguageId";
        $result = $this->dataBase->query($query);
        if ($this->dataBase->numRows($result)) {
            return $this->dataBase->fetchArray($result);
        }

        return null;
    }

    public function getClientOrders($clientId, $langId)
    {
        $orders = array();
        $query = "Select orderbidengin.bids_count as bidsCount,
        orderbidengin.deliveryWay, orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.CostLoad as cod, receiverAddress.name as receiverName, receiverAddress.phone_number as receiverPhone,
        orderbidengin.orderfinished, orderbidengin.isAcceptDelivery, orderbidengin.IsDeleted, orderbidengin.pickup_date,
		orderbidengin.AssignedByClient, orderbidengin.ClientAssignAccepted, orderbidengin.IsStuckOrder, orderbidengin.StuckOrderComment, orderbidengin.IsReturnedOrder, orderbidengin.IsReturnAccepted,
		orderbidaddress.OtherDetails as fromAddress, orderbidaddress.IdCity as fromCity, timeline.transporter_bidprice as delieryPrice, orderbidengin.currency, orderbidengin.from_currency_value,
        orderbidaddress.OtherDetailsDes as toAddress, orderbidaddress.IdCityDes as toCity,
		fromCityRegion.name as fromCityName,toCityRegion.name as toCityName, fromArea.name as fromAreaName, toArea.name as toAreaName
        From togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
		left outer join togo.cityLang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
		left outer join togo.cityLang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
        left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId
		left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        left outer join togo.transporterstimelinetb as timeline on orderbidengin.id = timeline.order_id and timeline.isCurrent = 1
        where orderbidengin.customerId='$clientId' and fromCityRegion.languageId = '$langId' and fromArea.languageId = '$langId' and toArea.languageId = '$langId'
		and toCityRegion.languageId = '$langId' and orderbidengin.IsDeleted IS NULL 
		and orderbidengin.Orderfinished IS NULL order by orderbidengin.id desc";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }

    public function getClientFinishedOrders($clientId, $langId)
    {
        // (select count(deliveryacceptordertable.IdOrder) from deliveryacceptordertable where deliveryacceptordertable.IdOrder = orderbidengin.id) as bidsCount
        $orders = array();
        $query = "Select orderbidengin.bids_count as bidsCount,
        orderbidengin.deliveryWay, orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.CostLoad as cod, receiverAddress.name as receiverName, receiverAddress.phone_number as receiverPhone,
        orderbidengin.orderfinished, orderbidengin.isAcceptDelivery, orderbidengin.IsDeleted, orderbidengin.pickup_date,
        orderbidengin.AssignedByClient, orderbidengin.ClientAssignAccepted, orderbidengin.IsStuckOrder, orderbidengin.StuckOrderComment, orderbidengin.IsReturnedOrder, orderbidengin.IsReturnAccepted,
		orderbidaddress.OtherDetails as fromAddress, orderbidaddress.OtherDetailsDes as toAddress, timeline.transporter_bidprice as deliveryPrice,
		orderbidaddress.IdCity as fromCity, orderbidaddress.IdCityDes as toCity, fromArea.name as fromAreaName, toArea.name as toAreaName, orderbidengin.currency, orderbidengin.from_currency_value,
		fromCityRegion.name as fromCityName,toCityRegion.name as toCityName
        From togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
		left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
		left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
        left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId
		left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        left outer join togo.transporterstimelinetb as timeline on orderbidengin.id = timeline.order_id and timeline.isCurrent = 1
        where orderbidengin.customerId='$clientId' and fromCityRegion.languageId = '$langId' and fromArea.languageId = '$langId' and toArea.languageId = '$langId'
		and toCityRegion.languageId = '$langId' and orderbidengin.IsDeleted IS NULL and orderbidengin.Orderfinished = '1'
        order by orderbidengin.id desc";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }

    /* edited (get all orders related to the transporter using the timeline table including assigned orders) */
    /* replaced with "getTransporterRelatedOrdersByPage" function below to meat pagination display requirements (for web) */
    /* public function getTransporterRelatedOrders($filter, $transporterId, $langId)
    {
        // get orders ids from transporterstimelinetb related to this transporter
        $query_get_order_id = "select order_id from transporterstimelinetb where transporter_id='$transporterId'";
        $result_get_order_id = $this->dataBase->query($query_get_order_id);
        $orders_ids = array();

        while ($row = $this->dataBase->fetchArray($result_get_order_id)) {
            array_push($orders_ids, $row['order_id']);
        }
        
        $strArr = implode(",", $orders_ids);

        $whereQuery = "";
        switch ($filter) {
            case "NEW_ORDERS":
                $whereQuery = " orderbidengin.DeliveryId IS NULL AND (orderbidengin.IsCreatedByTransporter <> '1' || orderbidengin.CreatedByTransporterId='$transporterId') AND orderbidaddress.IdCity in (select CityId from transporterworkcity where CustomerId = '$transporterId') AND orderbidaddress.IdCityDes in (select CityId from transporterworkcity where CustomerId = '$transporterId')";
                break;
            case "MY_ORDERS":
                if ($strArr)
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId' or orderbidengin.id IN (".$strArr.")) and orderbidengin.Orderfinished IS NULL";
                else 
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and orderbidengin.Orderfinished IS NULL";
                break;
            case "DELIVERED":
                if ($strArr)
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId' or orderbidengin.id IN (".$strArr.")) and orderbidengin.Orderfinished=1";
                else
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and orderbidengin.Orderfinished=1";
                break;
        }

        $whereQuery .= " and ";


        $orders = array();

        // edited (add order_status, IsReturnedOrder, IsStuckOrder) 
        $query = "Select (select count(deliveryacceptordertable.IdOrder) from deliveryacceptordertable where deliveryacceptordertable.IdOrder = orderbidengin.id) as bidsCount, orderbidengin.deliveryWay, orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id,
        orderbidengin.orderfinished, orderbidengin.order_status, orderbidengin.isAcceptDelivery, orderbidengin.IsDeleted, orderbidengin.pickup_date, orderbidengin.transporterAssignStatus, orderbidengin.AssignerId,
		orderbidengin.AssignedByClient, orderbidengin.ClientAssignAccepted,
		orderbidengin.IsStuckOrder, orderbidengin.StuckOrderComment, orderbidengin.IsReturnedOrder ,
		orderbidaddress.OtherDetails as fromAddress,orderbidengin.OriginalDeliveryId as OriginalDeliveryId,
        orderbidaddress.OtherDetailsDes as toAddress,orderbidengin.DeliveryId as DeliveryId,
		orderbidaddress.IdCity as fromCity, orderbidaddress.IdCityDes as toCity,
		fromCityRegion.CityName as fromCityName,toCityRegion.CityName as toCityName
        From orderbidengin inner join orderbidaddress
        On orderbidengin.id = orderbidaddress.idorderbidengin
		left outer join cityregionlang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.CityId
		left outer join cityregionlang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.CityId
        where $whereQuery
        orderbidengin.IsDeleted IS NULL and fromCityRegion.languageId = '$langId' and toCityRegion.languageId = '$langId'
        order by orderbidengin.id desc";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    } */

    /* edited (a new function added to suit display with pagination. to get orders by page (for web)) */
    public function getTransporterRelatedOrdersByPage($filter, $transporterId, $langId, $PageSize, $PageNumber, $searchStr)
    {

        /*  echo $searchStr;
        return; */

        $PageNumber = $this->dataBase->escape($PageNumber);
        $PageSize = $this->dataBase->escape($PageSize);

        if ($PageSize < 0)
            $PageSize = 0;
        $PageNumber = $PageNumber * $PageSize;

        // get orders ids from transporterstimelinetb related to this transporter
        $query_get_order_id = "select order_id from togo.transporterstimelinetb where transporter_id='$transporterId'";
        $result_get_order_id = $this->dataBase->query($query_get_order_id);
        $orders_ids = array();

        while ($row = $this->dataBase->fetchArray($result_get_order_id)) {
            array_push($orders_ids, $row['order_id']);
        }

        $strArr = implode(",", $orders_ids);

        // edited (for new orders, transporterworkcity.deleted is checked to get non-deleted cities for this transporter (if deleted=0))
        $whereQuery = "";
        switch ($filter) {
            case "NEW_ORDERS":
                $whereQuery = " orderbidengin.id = 0 AND orderbidengin.DeliveryId IS NULL AND orderbidengin.IsDeleted IS NULL AND (orderbidengin.IsCreatedByTransporter <> '1' || orderbidengin.CreatedByTransporterId='$transporterId') AND orderbidaddress.IdCity in (select CityId from transporterworkcity where CustomerId = '$transporterId' and deleted=0) AND orderbidaddress.IdCityDes in (select CityId from transporterworkcity where CustomerId = '$transporterId' and deleted=0)"; // edited ("and deleted=0" added)
                break;
            case "MY_ORDERS":
                if ($strArr)
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId' or orderbidengin.id IN (" . $strArr . ")) and orderbidengin.Orderfinished IS NULL and orderbidengin.IsDeleted IS NULL";
                else
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and orderbidengin.Orderfinished IS NULL and orderbidengin.IsDeleted IS NULL";
                break;
            case "DELIVERED":
                if ($strArr)
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId' or orderbidengin.id IN (" . $strArr . ")) and (orderbidengin.Orderfinished=1 or orderbidengin.IsDeleted=1) and orderbidengin.isReviewed = 0";
                else
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and (orderbidengin.Orderfinished=1 or orderbidengin.IsDeleted=1) and orderbidengin.isReviewed = 0";
                break;
            case "REVIEWED":
                if ($strArr)
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId' or orderbidengin.id IN (" . $strArr . ")) and (orderbidengin.Orderfinished=1 or orderbidengin.IsDeleted=1) and orderbidengin.isReviewed = 1";
                else
                    $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and (orderbidengin.Orderfinished=1 or orderbidengin.IsDeleted=1) and orderbidengin.isReviewed = 1";
                break;
        }

        $whereQuery .= " and ";

        $searchQuery = $searchStr == "no_str" ? "" : " AND (orderbidengin.id LIKE '%" . $searchStr . "%' OR foreign_id_table.foreignOrderId LIKE '%" . $searchStr . "%' OR foreign_id_table.loges_barcode LIKE '%" . $searchStr . "%') ";

        $orders = array();

        /* edited (add order_status, IsReturnedOrder, IsStuckOrder) (and filter by page) */
        $query = "Select orderbidengin.bids_count as bidsCount, orderbidengin.deliveryWay, orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.isReviewed, orderbidengin.CostLoad as COD,
        orderbidengin.orderfinished, orderbidengin.order_status, orderbidengin.isAcceptDelivery, orderbidengin.IsDeleted, orderbidengin.pickup_date, orderbidengin.transporterAssignStatus, orderbidengin.AssignerId,
		orderbidengin.AssignedByClient, orderbidengin.ClientAssignAccepted, foreign_id_table.foreignOrderId, foreign_id_table.loges_barcode,
		orderbidengin.IsStuckOrder, orderbidengin.StuckOrderComment, orderbidengin.IsReturnedOrder, orderbidengin.IsReturnAccepted,
		orderbidaddress.OtherDetails as fromAddress,orderbidengin.OriginalDeliveryId as OriginalDeliveryId,
        orderbidaddress.OtherDetailsDes as toAddress,orderbidengin.DeliveryId as DeliveryId,
		orderbidaddress.IdCity as fromCity, orderbidaddress.IdCityDes as toCity,
		fromCityRegion.name as fromCityName,toCityRegion.name as toCityName, receiverAddress.name as receiverName
        From togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
		left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
		left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
        left outer join togo.foreign_order_id_conversion as foreign_id_table on orderbidengin.id = foreign_id_table.togoOrderId
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        where $whereQuery
        fromCityRegion.languageId = '$langId' and toCityRegion.languageId = '$langId' " . $searchQuery . "
        order by orderbidengin.createdAt desc limit $PageSize offset $PageNumber"; // edited (citylang -> arealang / fromCityRegion.CityId -> fromCityRegion.areaId / toCityRegion.CityId -> toCityRegion.areaId)

        /* edited (get total orders count) */
        $query_Get_Orders_Total = "Select count(*) as TotalOrders 
        From togo.orderbidengin as orderbidengin 
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin 
        left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId 
        left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId 
        left outer join togo.foreign_order_id_conversion as foreign_id_table on orderbidengin.id = foreign_id_table.togoOrderId
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        where $whereQuery orderbidengin.IsDeleted IS NULL and fromCityRegion.languageId = '$langId' and toCityRegion.languageId = '$langId' " . $searchQuery;
        $result_get_Order_Total = $this->dataBase->query($query_Get_Orders_Total);
        $row_total = $this->dataBase->fetchArray($result_get_Order_Total);

        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        echo json_encode(array("server_response" => $orders, "total_orders" => $row_total['TotalOrders']));

        // return $orders;
    }

    /* edited ("getTransporterOrders" replaced with the function above "getTransporterRelatedOrders") (replaced only for web) */
    public function getTransporterOrders($filter, $transporterId, $langId)
    {
        $whereQuery = "";
        switch ($filter) {
            case "NEW_ORDERS":
                $whereQuery = " orderbidengin.DeliveryId IS NULL AND (orderbidengin.IsCreatedByTransporter <> '1' || orderbidengin.CreatedByTransporterId='$transporterId') AND orderbidaddress.IdCity in (select CityId from transporterworkcity where CustomerId = '$transporterId') AND orderbidaddress.IdCityDes in (select CityId from transporterworkcity where CustomerId = '$transporterId')";
                break;
            case "MY_ORDERS":
                $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and orderbidengin.Orderfinished IS NULL";
                break;
            case "DELIVERED":
                $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and orderbidengin.Orderfinished=1";
                break;
        }

        $whereQuery .= " and ";


        $orders = array();

        // *client name, *sender name, *receiver name, *sender number, *receiver number, *order date, *pickup date, *finish date, cod*, *status, *from area, *from city, *to area, *to city
        $query = "Select orderbidengin.bids_count as bidsCount, orderbidengin.deliveryWay, orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.dateFinished, orderbidengin.CostLoad as cod,
        orderbidengin.orderfinished, orderbidengin.order_status, orderbidengin.isAcceptDelivery, orderbidengin.IsDeleted, orderbidengin.pickup_date, orderbidengin.transporterAssignStatus, orderbidengin.AssignerId,
		orderbidengin.AssignedByClient, orderbidengin.ClientAssignAccepted, senderAddress.phone_number as senderPhone, senderAddress.name as senderName, receiverAddress.phone_number as receiverPhone, receiverAddress.name as receiverName, customer.PhoneNumber as clientPhone, clientbusinesstable.BusinessName as clientBusinessName,
		orderbidengin.IsStuckOrder, orderbidengin.StuckOrderComment, orderbidengin.IsReturnedOrder, orderbidengin.IsReturnAccepted,
		orderbidaddress.OtherDetails as fromAddress,orderbidengin.OriginalDeliveryId as OriginalDeliveryId,
        orderbidaddress.OtherDetailsDes as toAddress,orderbidengin.DeliveryId as DeliveryId,
		orderbidaddress.IdCity as fromCity, orderbidaddress.IdCityDes as toCity,
        orderbidaddress.IdArea as fromArea, orderbidaddress.IdAreaDes as toArea,
        fromAreaRegion.name as fromAreaName, toAreaRegion.name as toAreaName,
		fromCityRegion.name as fromCityName, toCityRegion.name as toCityName
        From togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.IdOrderBidEngin
        inner join togo.customer as customer on orderbidengin.CustomerId = customer.id
        inner join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
        left outer join togo.arealang as fromAreaRegion on orderbidaddress.IdArea = fromAreaRegion.areaId
		left outer join togo.arealang as toAreaRegion on orderbidaddress.IdAreaDes = toAreaRegion.areaId
		left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
		left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
        left outer join togo.addresses as senderAddress on orderbidaddress.SenderAddressId = senderAddress.id
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        where $whereQuery
        orderbidengin.IsDeleted IS NULL and fromCityRegion.languageId = '$langId' and toCityRegion.languageId = '$langId' and fromAreaRegion.languageId = '$langId' and toAreaRegion.languageId = '$langId'
        order by orderbidengin.id desc";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }

    public function getTransporterOrdersTest($filter, $transporterId, $langId)
    {
        $whereQuery = "";
        switch ($filter) {
            case "NEW_ORDERS":
                $whereQuery = " orderbidengin.DeliveryId IS NULL AND (orderbidengin.IsCreatedByTransporter <> '1' || orderbidengin.CreatedByTransporterId='$transporterId') AND orderbidaddress.IdCity in (select CityId from transporterworkcity where CustomerId = '$transporterId') AND orderbidaddress.IdCityDes in (select CityId from transporterworkcity where CustomerId = '$transporterId')";
                break;
            case "MY_ORDERS":
                $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and orderbidengin.Orderfinished IS NULL";
                break;
            case "DELIVERED":
                $whereQuery = " (orderbidengin.DeliveryId='$transporterId' or orderbidengin.OriginalDeliveryId='$transporterId') and orderbidengin.Orderfinished=1";
                break;
        }

        $whereQuery .= " and ";


        $orders = array();

        // *client name, *sender name, *receiver name, *sender number, *receiver number, *order date, *pickup date, *finish date, cod*, *status, *from area, *from city, *to area, *to city
        $query = "Select orderbidengin.bids_count as bidsCount, orderbidengin.deliveryWay, orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.dateFinished, orderbidengin.CostLoad as cod,
        orderbidengin.orderfinished, orderbidengin.order_status, orderbidengin.isAcceptDelivery, orderbidengin.IsDeleted, orderbidengin.pickup_date, orderbidengin.transporterAssignStatus, orderbidengin.AssignerId,
		orderbidengin.AssignedByClient, orderbidengin.ClientAssignAccepted, senderAddress.phone_number as senderPhone, senderAddress.name as senderName, receiverAddress.phone_number as receiverPhone, receiverAddress.name as receiverName, customer.PhoneNumber as clientPhone, clientbusinesstable.BusinessName as clientBusinessName,
		orderbidengin.IsStuckOrder, orderbidengin.StuckOrderComment, orderbidengin.IsReturnedOrder, orderbidengin.IsReturnAccepted,
		orderbidaddress.OtherDetails as fromAddress,orderbidengin.OriginalDeliveryId as OriginalDeliveryId,
        orderbidaddress.OtherDetailsDes as toAddress,orderbidengin.DeliveryId as DeliveryId,
		orderbidaddress.IdCity as fromCity, orderbidaddress.IdCityDes as toCity,
        orderbidaddress.IdArea as fromArea, orderbidaddress.IdAreaDes as toArea,
        fromAreaRegion.name as fromAreaName, toAreaRegion.name as toAreaName,
		fromCityRegion.name as fromCityName, toCityRegion.name as toCityName
        From togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.IdOrderBidEngin
        inner join togo.customer as customer on orderbidengin.CustomerId = customer.id
        inner join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
        left outer join togo.arealang as fromAreaRegion on orderbidaddress.IdArea = fromAreaRegion.areaId
		left outer join togo.arealang as toAreaRegion on orderbidaddress.IdAreaDes = toAreaRegion.areaId
		left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
		left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
        left outer join togo.addresses as senderAddress on orderbidaddress.SenderAddressId = senderAddress.id
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        where $whereQuery
        orderbidengin.IsDeleted IS NULL and fromCityRegion.languageId = '$langId' and toCityRegion.languageId = '$langId' and fromAreaRegion.languageId = '$langId' and toAreaRegion.languageId = '$langId'
        order by orderbidengin.id desc";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }

    public function getTransporterTeamOrders($filter, $transporterId, $langId)
    {
        $whereQuery = "";
        switch ($filter) {
            case "MY_ORDERS":
                $whereQuery = " orderbidengin.TeamMemberId='$transporterId' and orderbidengin.IsTeamOrder=1 and orderbidengin.IsFinishedByMember IS NULL and orderbidengin.Orderfinished IS NULL";
                break;
            case "DELIVERED":
                $whereQuery = " orderbidengin.TeamMemberId='$transporterId' and orderbidengin.IsTeamOrder=1 and orderbidengin.IsFinishedByMember = 1 and orderbidengin.Orderfinished = 1";
                break;
        }

        $whereQuery .= " and ";
        $orders = array();
        $query = "Select orderbidengin.bids_count as bidsCount, orderbidengin.deliveryWay, orderbidengin.TypeLoad,
		orderbidengin.DateLoad, orderbidengin.id, orderbidengin.orderfinished, orderbidengin.isAcceptDelivery, orderbidengin.IsDeleted,
		orderbidengin.pickup_date, orderbidaddress.OtherDetails as fromAddress, orderbidaddress.OtherDetailsDes as toAddress,
        orderbidaddress.IdCity as fromCity, orderbidaddress.IdCityDes as toCity,
		fromCityRegion.name as fromCityName,toCityRegion.name as toCityName
		From togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
        left outer join togo.arealang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.areaId
		left outer join togo.arealang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.areaId
		where $whereQuery orderbidengin.IsDeleted IS NULL and fromCityRegion.languageId = '$langId' 
		and toCityRegion.languageId = '$langId' order by orderbidengin.id desc";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }
}
