<?php

class AdminService
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

    // functions goes here ...

    // select description from togo.actionsrecordstb where order_id = '$orderId' order by id desc limit 1
    // left outer join togo.actionsrecordstb as action on orderbidengin.id = action.order_id and 

    public function getAllActiveOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT (select count(deliveryacceptordertable.IdOrder) from togo.deliveryacceptordertable as deliveryacceptordertable 
            where deliveryacceptordertable.IdOrder = orderbidengin.id) as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder,
            orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, orderbidengin.foreign_order_error, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, fromCityRegion.name as fromCity, toCityRegion.name as toCity, orderbidaddress.OtherDetailsDes as toAddress, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId AND fromCityRegion.languageId = '1'
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId AND toCityRegion.languageId = '1'
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId AND toArea.languageId = '1'
            left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId AND fromArea.languageId = '1'
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId
            
            where  orderbidengin.deliveryId IS NOT NULL AND orderbidengin.Orderfinished IS NULL AND (orderbidengin.IsDeleted IS NULL OR orderbidengin.IsDeleted=0) and orderbidengin.IsReturnedOrder=0 and orderbidengin.IsStuckOrder=0
            " . $searchQuery . "
            order by orderbidengin.id desc";

            // left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }

    public function getAllActiveFoodOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT (select count(deliveryacceptordertable.IdOrder) from togo.deliveryacceptordertable as deliveryacceptordertable 
            where deliveryacceptordertable.IdOrder = orderbidengin.id) as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder,
            orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, orderbidengin.foreign_order_error, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, fromCityRegion.name as fromCity, toCityRegion.name as toCity, orderbidaddress.OtherDetailsDes as toAddress, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
            left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId
            
            where orderbidengin.TypeLoad = 1 AND (orderbidengin.order_status='Bid Accepted' OR orderbidengin.order_status='Out for Delivery')
            and fromCityRegion.languageId = '1' and toCityRegion.languageId = '1' and toArea.languageId = '1' and fromArea.languageId = '1' " . $searchQuery . "
            order by orderbidengin.id desc";

            // left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }

    public function GetAllMarkedOrders()
    {
        $query = "SELECT orderbidengin.bids_count as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder,
            orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, orderbidengin.foreign_order_error, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, fromCityRegion.name as fromCity, toCityRegion.name as toCity, orderbidaddress.OtherDetailsDes as toAddress, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
            From togo.orderbidengin as orderbidengin 
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId and fromCityRegion.languageId = '1'
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId and toCityRegion.languageId = '1'
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId and toArea.languageId = '1'
            left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId and fromArea.languageId = '1'
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId
            
            where  orderbidengin.foreign_order_error = 1
            order by orderbidengin.id desc";

            // left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }
        return $orders;
    }

    public function getAllFinishedOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad, orderbidengin.dateFinished, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder,
            orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, orderbidaddress.OtherDetailsDes as toAddress, orderbidengin.foreign_order_error,
            fromCityRegion.name as fromCity, toCityRegion.name as toCity, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId AND fromCityRegion.languageId = '1'
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId AND toCityRegion.languageId = '1'
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId and toArea.languageId = '1'
            left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId and fromArea.languageId = '1'
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId

            where orderbidengin.deliveryId IS NOT NULL AND orderbidengin.Orderfinished=1 " . $searchQuery . $dateQuery . "
            order by orderbidengin.dateFinished desc";
            // " . $searchQuery . $dateQuery . "

        //left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        /* (select count(deliveryacceptordertable.IdOrder) from togo.deliveryacceptordertable as deliveryacceptordertable 
            where deliveryacceptordertable.IdOrder = orderbidengin.id) as bidsCount */

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function getAllFinishedFoodOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad, orderbidengin.dateFinished, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder,
            orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, orderbidaddress.OtherDetailsDes as toAddress, orderbidengin.foreign_order_error,
            fromCityRegion.name as fromCity, toCityRegion.name as toCity, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
            left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId

            where orderbidengin.TypeLoad = 1 AND orderbidengin.order_status='Delivered' " . $searchQuery . $dateQuery . "
            AND fromCityRegion.languageId = '1' AND toCityRegion.languageId = '1' and toArea.languageId = '1' and fromArea.languageId = '1'
            order by orderbidengin.dateFinished desc";
            // " . $searchQuery . $dateQuery . "

        //left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        /* (select count(deliveryacceptordertable.IdOrder) from togo.deliveryacceptordertable as deliveryacceptordertable 
            where deliveryacceptordertable.IdOrder = orderbidengin.id) as bidsCount */

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function GetAllDeletedOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, orderbidengin.CostLoad, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder, receiverAddress.name as receiverName,
            orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, orderbidengin.foreign_order_error,
            orderbidaddress.OtherDetails as fromAddress, orderbidaddress.OtherDetailsDes as toAddress,
            fromCityRegion.name as fromCity, toCityRegion.name as toCity, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId AND fromCityRegion.languageId = '1'
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId AND toCityRegion.languageId = '1'
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId and toArea.languageId = '1'
            left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId and fromArea.languageId = '1'
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId
            
            where orderbidengin.IsDeleted=1 
            " . $searchQuery . $dateQuery . "
            order by orderbidengin.updatedAt desc";

            // left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function GetAllDeletedFoodOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, orderbidengin.CostLoad, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder, receiverAddress.name as receiverName,
            orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, orderbidengin.foreign_order_error,
            orderbidaddress.OtherDetails as fromAddress, orderbidaddress.OtherDetailsDes as toAddress,
            fromCityRegion.name as fromCity, toCityRegion.name as toCity, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
            left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId
            
            where orderbidengin.TypeLoad = 1 AND orderbidengin.order_status='Deleted' 
            AND fromCityRegion.languageId = '1' AND toCityRegion.languageId = '1' and toArea.languageId = '1' and fromArea.languageId = '1' " . $searchQuery . $dateQuery . "
            order by orderbidengin.updatedAt desc";

            // left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function GetAllReturnedOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, orderbidengin.CostLoad, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder, receiverAddress.name as receiverName,
        orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
        orderbidaddress.OtherDetails as fromAddress, fromCityRegion.name as fromCity, toCityRegion.name as toCity, orderbidaddress.OtherDetailsDes as toAddress, orderbidengin.foreign_order_error, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
        From togo.orderbidengin as orderbidengin 
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
        left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
        left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
        left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
        left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
        left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
        left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId
        
        where (orderbidengin.IsReturnAccepted=1 or orderbidengin.IsStuckOrder=1) and orderbidengin.Orderfinished IS NULL and orderbidengin.IsDeleted IS NULL
        and fromCityRegion.languageId = '1' and toCityRegion.languageId = '1' and toArea.languageId = '1' and fromArea.languageId = '1' " . $searchQuery . $dateQuery . "
        order by orderbidengin.updatedAt desc";

        // left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function GetAllExceptionOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        // $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad, orderbidengin.dateFinished, orderbidengin.IsReturnAccepted, orderbidengin.IsReturnedOrder,
        orderbidengin.TypeLoad , orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
        orderbidaddress.OtherDetails as fromAddress, orderbidaddress.OtherDetailsDes as toAddress, orderbidengin.foreign_order_error,
        fromCityRegion.name as fromCity, toCityRegion.name as toCity, toArea.name as toArea, fromArea.name as fromArea, orderbidengin.last_action as errMsg, foreignId.foreignOrderId, foreignId.loges_barcode as foreignOrderBarcode
        From togo.orderbidengin as orderbidengin
        inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
        left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
        left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
        left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
        left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
        left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
        left outer join togo.arealang as fromArea on orderbidaddress.IdArea = fromArea.areaId
        left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
        left outer join togo.foreign_order_id_conversion as foreignId on orderbidengin.id = foreignId.togoOrderId
        
        where foreign_order_error = 1
        and fromCityRegion.languageId = '1' and toCityRegion.languageId = '1' and toArea.languageId = '1' and fromArea.languageId = '1' " . $searchQuery . "
        order by orderbidengin.updatedAt desc";

        // left outer join togo.actionsrecordstb as action on action.id = (select action2.id from togo.actionsrecordstb as action2 where action2.order_id = orderbidengin.id order by action2.id desc limit 1)

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function getAllNewOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad,
            orderbidengin.TypeLoad, orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, fromCityRegion.name as fromCity, toCityRegion.name as toCity, orderbidaddress.OtherDetailsDes as toAddress, orderbidengin.foreign_order_error, toArea.name as toArea
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            where orderbidengin.DeliveryId IS NULL AND orderbidengin.IsDeleted IS NULL
            AND fromCityRegion.languageId = '1' AND toCityRegion.languageId = '1' and toArea.languageId = '1' " . $searchQuery . $dateQuery . "
            order by orderbidengin.id desc";

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function getAllNewFoodOrders($searchStr, $filterDate)
    {
        list($startDate, $endDate) = explode(" -- ", $filterDate);

        // file_put_contents("datotototototo.log", var_export($filterDate, true) . "\n ================ \n", FILE_APPEND);

        $searchQuery = $searchStr == "no_str" ? "" : " AND  (orderbidengin.id LIKE '%" . $searchStr . "%' OR receiverAddress.name LIKE '%" . $searchStr . "%' OR transportertable.AccountName LIKE '%" . $searchStr . "%' OR clientbusinesstable.BusinessName LIKE '%" . $searchStr . "%') ";
        $dateQuery = " AND (DATE_FORMAT(DateLoad, '%Y-%m') BETWEEN '$startDate' AND '$endDate' OR DATE_FORMAT(DateLoad, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate') ";

        $query = "SELECT orderbidengin.bids_count as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad,
            orderbidengin.TypeLoad, orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, fromCityRegion.name as fromCity, toCityRegion.name as toCity, orderbidaddress.OtherDetailsDes as toAddress, orderbidengin.foreign_order_error, toArea.name as toArea
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
            left outer join togo.arealang as toArea on orderbidaddress.IdAreaDes = toArea.areaId
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            where orderbidengin.TypeLoad = 1 AND orderbidengin.order_status='Waiting for Bids'
            AND fromCityRegion.languageId = '1' AND toCityRegion.languageId = '1' and toArea.languageId = '1' " . $searchQuery . $dateQuery . "
            order by orderbidengin.id desc";

        $orders = array();
        $result = $this->dataBase->query($query);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return $orders;
    }

    public function getDateFormat($dateParameter, $dateFormat)
    {
        $date_Param = strtotime($dateParameter);
        $DateFormat = date($dateFormat, $date_Param);

        return $DateFormat;
    }

    public function getOrderDetailsForAdmin($OrderId)
    {
        $villagevar = "";
        $villagedesvar = "";
        $OrderDetailsArray = array();

        $Query_Get_Order_Details = "SELECT
        OrderEngine.IsReturnedOrder,
        OrderEngine.IsReturnAccepted,
        OrderEngine.IsStuckOrder,
        OrderEngine.currentTransporterId,
        OrderEngine.StuckOrderComment,
        OrderEngine.deliveryWay As deliveryWay,
        OrderEngine.order_status,
        OrderEngine.transporterAssignStatus,
        OrderEngine.AssignerId,
        OrderEngine.AssigneeId,
        OrderEngine.clientAssigneeId,
        OrderEngine.TeamMemberId,
        OrderEngine.id As id,
        OrderEngine.CustomerId AS CustomerId,
        OrderEngine.pickup_date AS pickupDate,
        ClientCustomerTable.FirstName As FullNameCustomer,
        ClientCustomerTable.LastName As LastNameCustomer,
        ClientCustomerTable.CustomerId,
        OrderEngine.DetailsLoad As DetailsLoad,
        OrderEngine.TypeLoad As TypeLoad,
        OrderEngine.foreign_order_error,
        OrderEngine.LengthLoad As LengthLoad,
        OrderEngine.CostLoad as CostLoad,
        OrderEngine.DeliveryCost as DeliveryCost,
        OrderEngine.WidthLoad As WidthLoad,
        OrderEngine.HeightLoad As HeightLoad,
        OrderEngine.HeightLoad As HeightLoad,
        OrderEngine.qr_code as BarCode,
        newCod,
        OrderEngine.WeightLoad As WeightLoad,
        OrderEngine.DateLoad As DateLoad,
        OrderEngine.orderfinished,
        OrderEngine.isAcceptDelivery,
        OrderEngine.IsDeleted,
        OrderEngine.currency,
        OrderEngine.from_currency_value,
        OrderBidAddress.IdCity As IdCitySource,
        OrderBidAddress.IdCityDes As IdCityDes,
        OrderBidAddress.IdArea As IdAreaSource,
        OrderBidAddress.IdAreaDes As IdAreaDes,
        OrderBidAddress.SenderAddressId as SenderAddressId,
        OrderBidAddress.OtherDetails As OtherDetails,
        OrderBidAddress.LatSender As LatSender,
        OrderBidAddress.LongSender As LongSender,
        OrderBidAddress.LatReciver As LatReciver,
        OrderBidAddress.LongReciver As LongReciver,
        CustomerTable.PhoneNumber As PhoneCustomer,
        OrderBidAddress.OtherDetailsDes As OtherDetailsDes,
        receiverAddresses.name as ReceiverName,
        receiverAddresses.phone_number AS ReceiverAddressNum,
        receiverAddresses.foreign_area_en_name AS receiverForeignViilageName,
        receiverAddresses.foreign_region_en_name AS receiverForeignRegionName,
        senderAddresses.foreign_area_en_name AS senderForeignViilageName,
        senderAddresses.foreign_region_en_name AS senderForeignRegionName,
        OrderEngine.IsAssignAccepted,
        OrderEngine.AssignedByClient,
        OrderEngine.ClientAssignAccepted,
        OrderEngine.DeliveryId,
        OrderEngine.OriginalDeliveryId
		From
        togo.OrderBidEngin As OrderEngine, 
        togo.OrderBidAddress As OrderBidAddress, 
        togo.Customer As CustomerTable, 
        togo.ClientTable As ClientCustomerTable,
        togo.Addresses As receiverAddresses,
        togo.Addresses As senderAddresses
        Where
		OrderBidAddress.IdOrderBidEngin=OrderEngine.id
        AND OrderEngine.id = '$OrderId'
        AND OrderBidAddress.ReciverAddressId = receiverAddresses.id 
        AND OrderBidAddress.SenderAddressId = senderAddresses.id 
        AND CustomerTable.id = OrderEngine.CustomerId
        AND ClientCustomerTable.CustomerId = CustomerTable.id";

        $Result_Get_Details_Array = $this->dataBase->query($Query_Get_Order_Details);
        $row_Details_Order = $this->dataBase->fetchArray($Result_Get_Details_Array);
        $CustomerId = $row_Details_Order['CustomerId'];
        $SenderAddressId = $row_Details_Order['SenderAddressId'];

        $query_Get_Customer_Img = "Select LogoUrl as customerImgURL From togo.clientbusinesstable Where CustomerId='$CustomerId'";
        $result_Get_Customer_Img = $this->dataBase->query($query_Get_Customer_Img);
        $row_CustomerImg = $this->dataBase->fetchArray($result_Get_Customer_Img);

        $CityIdSource = $row_Details_Order['IdCitySource'];
        $CityIdDestination = $row_Details_Order['IdCityDes'];
        $AreaIdSource = $row_Details_Order['IdAreaSource'];
        $AreaIdDestination = $row_Details_Order['IdAreaDes'];

        $query_GetCostDelivery = "select CostDelivery from togo.DeliveryAcceptOrderTable where IdOrder = '$OrderId'";
        $result_GetCostDelivery = $this->dataBase->query($query_GetCostDelivery);
        $row_GetCostDelivery = $this->dataBase->fetchArray($result_GetCostDelivery);

        $query_GetCitySource = "Select name From togo.citylang Where languageId=1 AND cityId='$CityIdSource'";
        $result_CitySource = $this->dataBase->query($query_GetCitySource);
        $row_CitySource = $this->dataBase->fetchArray($result_CitySource);

        $query_GetCityDes = "Select name From togo.citylang Where languageId=1 AND cityId='$CityIdDestination'";
        $result_CityDes = $this->dataBase->query($query_GetCityDes);
        $row_CityDes = $this->dataBase->fetchArray($result_CityDes);

        $query_GetAreaSource = "Select name From togo.arealang Where languageId=1 AND areaId='$AreaIdSource'";
        $result_AreaSource = $this->dataBase->query($query_GetAreaSource);
        $row_AreaSource = $this->dataBase->fetchArray($result_AreaSource);

        $query_GetAreaDes = "Select name From togo.arealang Where languageId=1 AND areaId='$AreaIdDestination'";
        $result_AreaDes = $this->dataBase->query($query_GetAreaDes);
        $row_AreaDes = $this->dataBase->fetchArray($result_AreaDes);

        $query_GetSenderName = "select name as SenderName from togo.addresses where id = '$SenderAddressId'";
        $result_GetSenderName = $this->dataBase->query($query_GetSenderName);
        $row_GetSenderName = $this->dataBase->fetchArray($result_GetSenderName);

        $DeliveryId = $row_Details_Order['DeliveryId'];

        $query_GetAssignInfo = "select PersonalImgPath as transporterImgURL, concat( trans.FirstName, ' ' , trans.LastName) as FullName  ,cust.PhoneNumber from togo.transportertable as trans inner join togo.customer as cust on cust.id=trans.CustomerId where cust.id = '$DeliveryId'";
        $result_GetAssignInfo = $this->dataBase->query($query_GetAssignInfo);
        $row_GetAssignInfo = $this->dataBase->fetchArray($result_GetAssignInfo);

        $OrderStatus = "";

        if ($row_Details_Order == true) {
            if ($row_Details_Order['Village'] != null || $row_Details_Order['Village'] != "") {
                $villagevar = $row_Details_Order['Village'];
            }
            if ($row_Details_Order['VillageDestination'] != null || $row_Details_Order['VillageDestination'] != "") {
                $villagedesvar = $row_Details_Order['VillageDestination'];
            }

            $assignStatus = "Not Assigned";
            $assignedMemberName = "";
            if ($row_Details_Order['TeamMemberId'] != null && $row_Details_Order['TeamMemberId'] != 'null'  && $row_Details_Order['TeamMemberId'] != 'NULL') {
                $memberTeamId = $row_Details_Order['TeamMemberId'];
                $query_getmember = "Select FirstName, LastName from togo.transportertable where CustomerId =$memberTeamId";
                $Result_getmember = $this->dataBase->query($query_getmember);
                if ($Result_getmember == true) {
                    $row_getmember = $this->dataBase->fetchArray($Result_getmember);
                    $assignedMemberName = $row_getmember['FirstName'] . " " . $row_getmember['LastName'];
                    $assignStatus = "Assigned";
                } else {
                    $assignStatus = "Not Assigned";
                }
            } else {
                $assignStatus = "Not Assigned";
            }

            array_push($OrderDetailsArray, array(
                "currentTransporterId" => $row_Details_Order['currentTransporterId'],
                "clientAssigneeId" => $row_Details_Order['clientAssigneeId'],
                "foreign_order_error" => $row_Details_Order['foreign_order_error'],
                "order_status" => $row_Details_Order['order_status'],
                "AssignerId" => $row_Details_Order['AssignerId'],
                "AssigneeId" => $row_Details_Order['AssigneeId'],
                "transporterAssignStatus" => $row_Details_Order['transporterAssignStatus'],
                "IsAssignAccepted" => $row_Details_Order['IsAssignAccepted'],
                "customerImgURL" => $row_CustomerImg['customerImgURL'],
                "transporterImgURL" => $row_GetAssignInfo['transporterImgURL'],
                "AssignToName" => $row_GetAssignInfo['FullName'],
                "AssignToNumber" => $row_GetAssignInfo['PhoneNumber'],
                "SenderName" => $row_GetSenderName['SenderName'],
                "DeliveryPrice" => $row_GetCostDelivery['CostDelivery'],
                "OrderStatus" => $OrderStatus,
                "deliveryWay" => $row_Details_Order['deliveryWay'],
                "HeightLoad" => $row_Details_Order['HeightLoad'],
                "id" => $row_Details_Order['id'],
                "newCod" => $row_Details_Order['newCod'],
                "isAcceptDelivery" => $row_Details_Order['isAcceptDelivery'],
                "orderFinished" => $row_Details_Order['orderFinished'],
                "currency" => $row_Details_Order['currency'],
                "from_currency_value" => $row_Details_Order['from_currency_value'],
                "IsStuckOrder" => $row_Details_Order['IsStuckOrder'],
                "StuckOrderComment" => $row_Details_Order['StuckOrderComment'],
                "IsReturnedOrder" => $row_Details_Order['IsReturnedOrder'],
                "IsReturnAccepted" => $row_Details_Order['IsReturnAccepted'],
                "AssignedByClient" => $row_Details_Order['AssignedByClient'],
                "ClientAssignAccepted" => $row_Details_Order['ClientAssignAccepted'],
                "IsDeleted" => $row_Details_Order['IsDeleted'],
                "FullNameCustomer" => $row_Details_Order['FullNameCustomer'],
                "CustomerId" => $row_Details_Order['CustomerId'],
                "BarCode" => $row_Details_Order['BarCode'],
                "DetailsLoad" => $row_Details_Order['DetailsLoad'],
                "LengthLoad" => $row_Details_Order['LengthLoad'],
                "WidthLoad" => $row_Details_Order['WidthLoad'],
                "HeightLoad" => $row_Details_Order['HeightLoad'], 
                "WeightLoad" => $row_Details_Order['WeightLoad'], 
                "CostLoad" => $row_Details_Order['CostLoad'], 
                "DeliveryCost" => $row_Details_Order['DeliveryCost'], 
                "DateLoad" => $this->getDateFormat($row_Details_Order['DateLoad'], 'Y-m-d H:i:s'), 
                "IdCitySource" => $row_CitySource['name'],
                "IdCityDes" => $row_CityDes['name'], 
                "IdAreaSource" => $row_AreaSource['name'], 
                "IdAreaDes" => $row_AreaDes['name'], 
                "NameNeighborhood" => $row_Details_Order['NameNeighborhood'], 
                "NameStreet" => $row_Details_Order['NameStreet'], 
                "NameBuilding" => $row_Details_Order['NameBuilding'], 
                "FloorNumbers" => $row_Details_Order['FloorNumbers'], 
                "ApartmentNumber" => $row_Details_Order['ApartmentNumber'], 
                "OtherDetails" => $row_Details_Order['OtherDetails'], 
                "OriginalDeliveryId" => $row_Details_Order['OriginalDeliveryId'], 
                "DeliveryId" => $row_Details_Order['DeliveryId'], 
                "LatSender" => $row_Details_Order['LatSender'], 
                "AssignStatus" => $assignStatus, 
                "AssignedMemberName" => $assignedMemberName, 
                "LongSender" => $row_Details_Order['LongSender'], 
                "LatReciver" => $row_Details_Order['LatReciver'], 
                "LongReciver" => $row_Details_Order['LongReciver'], 
                "TypeLoad" => $row_Details_Order['TypeLoad'], 
                "ReceiverName" => $row_Details_Order['ReceiverName'],  
                "LastNameCustomer" => $row_Details_Order['LastNameCustomer'], 
                "NameNeighborhoodDes" => $row_Details_Order['NameNeighborhoodDes'], 
                "NameStreetDes" => $row_Details_Order['NameStreetDes'],
                "NameBuildingDes" => $row_Details_Order['NameBuildingDes'], 
                "FloorNumbersDes" => $row_Details_Order['FloorNumbersDes'], 
                "ApartmentNumberDes" => $row_Details_Order['ApartmentNumberDes'], 
                "OtherDetailsDes" => $row_Details_Order['OtherDetailsDes'], 
                "PhoneCustomer" => $row_Details_Order['PhoneCustomer'], 
                "ReceiverAddressNum" => $row_Details_Order['ReceiverAddressNum'], 
                "village" => $villagevar, 
                "villageDes" => $villagedesvar, 
                "pickupDate" => $row_Details_Order['pickupDate'],
                "senderForeignViilageName" => $row_Details_Order['senderForeignViilageName'],
                "senderForeignRegionName" => $row_Details_Order['senderForeignRegionName'],
                "receiverForeignViilageName" => $row_Details_Order['receiverForeignViilageName'],
                "receiverForeignRegionName" => $row_Details_Order['receiverForeignRegionName']
            ));

            return $OrderDetailsArray;
        } else {
            echo "OrderNotFound";
        }
    }

    public function getOrderActionsForAdmin($orderId)
    {
        $query_get_records = "select * from togo.actionsrecordstb where order_id='$orderId' order by id desc";

        $records = array();
        $result = $this->dataBase->query($query_get_records);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($records, $row);
        }

        return $records;
    }

    public function getTimeLineForAdmin($orderId)
    {
        $query_get_timeline = "select *, concat(transportertable.FirstName, ' ', transportertable.LastName) as fullName, transportertable.PersonalImgPath, customer.PhoneNumber 
        from togo.transporterstimelinetb as transporterstimelinetb 
        inner join togo.transportertable as transportertable on transporterstimelinetb.transporter_id=transportertable.CustomerId 
        inner join togo.customer as customer on transporterstimelinetb.transporter_id=customer.id 
        where order_id='$orderId' order by transporterstimelinetb.id";
        $result_get_timeline = $this->dataBase->query($query_get_timeline);
        $timeline = array();

        while ($row = $this->dataBase->fetchArray($result_get_timeline)) {
            array_push($timeline, array("PersonalImgPath" => $row['PersonalImgPath'], "PhoneNumber" => $row['PhoneNumber'], "fullName" => $row['fullName'], "transporter_id" => $row['transporter_id'], "assign_date" => $row['assign_date'], "transporter_bidprice" => $row['transporter_bidprice'], "transporter_pickupdate" => $row['transporter_pickupdate'], "isCurrent" => $row['isCurrent']));
        }

        return $timeline;
    }

    public function getCustomerInfoForWayBill($orderId)
    {
        $customerId = "";

        $query_get_customerId = "select CustomerId from togo.orderbidengin where id='$orderId'";
        $result_get_customerId = $this->dataBase->query($query_get_customerId);
        $row = $this->dataBase->fetchArray($result_get_customerId);

        $customerId = $row['CustomerId'];

        $query_get_customerType = "select IsClient from togo.customer where id='$customerId'";
        $result_get_customerType = $this->dataBase->query($query_get_customerType);
        $row = $this->dataBase->fetchArray($result_get_customerType);

        if ($row['IsClient'] == 1) {
            $query_get_customerInfo = "select bus.LogoUrl, bus.BusinessName, client.Email, concat(client.FirstName, ' ', client.LastName) as FullName, cust.PhoneNumber 
            from togo.clienttable as client 
            inner join togo.clientbusinesstable as bus on client.CustomerId = bus.CustomerId 
            inner join togo.customer as cust on client.CustomerId = cust.id
            where client.CustomerId='$customerId'";
            $result_get_customerInfo = $this->dataBase->query($query_get_customerInfo);
            $row = $this->dataBase->fetchArray($result_get_customerInfo);

            return array("BusinessName" => $row['BusinessName'], "logoURL" => $row['LogoUrl'], "Email" => $row['Email'], "FullName" => $row['FullName'], "phone" => $row['PhoneNumber']);
        } else {
            $query_get_customerInfo = "select trans.Email, trans.AccountName as BusinessName, trans.PersonalImgPath as logoURL, concat(trans.FirstName, ' ', trans.LastName) as FullName from togo.transportertable as trans where CustomerId='$customerId'";
            $result_get_customerInfo = $this->dataBase->query($query_get_customerInfo);
            $row = $this->dataBase->fetchArray($result_get_customerInfo);

            return array("BusinessName" => $row['BusinessName'], "logoURL" => $row['LogoUrl'], "Email" => $row['Email'], "FullName" => $row['FullName']);
        }
    }

    public function checkForForeignIdForAdmin($OrderId)
    {
        $query_getForeignId = "
                    select foreignCo.companyId, foreignCo.foreignOrderId, foreignCo.loges_barcode, foreignCo.loges_barcode_img, superCo.logo_img_path as img, superCo.name, trans.super_foreign_company_id, trans.PersonalImgPath as trans_img, cust.PhoneNumber as trans_phone
                    from togo.foreign_order_id_conversion as foreignCo
                    inner join togo.transportertable as trans on foreignCo.companyId = trans.CustomerId
                    inner join togo.customer as cust on foreignCo.companyId = cust.id
                    inner join togo.foreign_transportation_companies as superCo on trans.super_foreign_company_id = superCo.id
                    where foreignCo.togoOrderId = '$OrderId'
                ";
        $result_getForeignId = $this->dataBase->query($query_getForeignId);

        if ($this->dataBase->numRows($result_getForeignId) > 0) {

            $row_getForeignId = $this->dataBase->fetchArray($result_getForeignId);

            return array("companyId" => $row_getForeignId['companyId'], "foreignOrderId" => $row_getForeignId['foreignOrderId'], "companyImgURL" => $row_getForeignId['img'], "AccountName" => $row_getForeignId['name'], "barcode" => $row_getForeignId['loges_barcode'], "barcode_img" => $row_getForeignId['loges_barcode_img'], "super_id" => $row_getForeignId['super_foreign_company_id'], "trans_img" => $row_getForeignId['trans_img'], "trans_phone" => $row_getForeignId['trans_phone']);
        } else {
            return "noForeigId";
        }
    }

    public function getAllClients()
    {
        $query = "select 
            cus.id, cus.IsTransporter, cus.PhoneNumber, cus.IsBlocked, CONCAT(tt.FirstName,' ',tt.LastName) as FullName, tt.IdClient as ID, tt.Email, bb.LogoUrl as PersonalImgPath, bb.BusinessName, bb.BusinessPlace
            from togo.customer as cus 
            inner join togo.clienttable as tt 
            inner join togo.clientbusinesstable as bb 
            on cus.id=tt.CustomerId and cus.id=bb.CustomerId
            where cus.IsClient = 1 and cus.deleted = 0";

        $clients = array();
        $result = $this->dataBase->query($query);
        $row_count_clients = $this->dataBase->numRows($result);
        while ($row = $this->dataBase->fetchArray($result)) {
            /* $result_Balance = $this->getBalance($row['id']);
                $row['balance'] = $result_Balance; */

            // get total cod for each client for new orders
            $tempClientId = $row['id'];
            $query_getTotalCOD = "select SUM(CAST(orderbidengin.CostLoad AS UNSIGNED)) as totalCOD from togo.orderbidengin as orderbidengin where CustomerId = '$tempClientId' and order_status = 'Waiting for Bids'";
            $result_getTotalCOD = $this->dataBase->query($query_getTotalCOD);
            $row_getTotalCOD = $this->dataBase->fetchArray($result_getTotalCOD);

            $row['totalCOD'] = $row_getTotalCOD['totalCOD'];

            array_push($clients, $row);
        }

        // sort $clients

        return array("clients_list" => $clients, "NumberOfClients" => $row_count_clients);
    }

    public function getAllTransporters()
    {
        $query = "select cus.id, cus.IsTransporter, cus.PhoneNumber, cus.IsBlocked, CONCAT(tt.FirstName,' ',tt.LastName) as FullName, tt.AccountName as BusinessName, tt.LicenceNumber, tt.Email,
            tt.PersonalImgPath, tt.IDNumber as ID 
            from togo.customer as cus 
            inner join togo.transportertable as tt on cus.id=tt.CustomerId
            where cus.IsTransporter = 1 and cus.deleted = 0";

        $transporters = array();
        $result = $this->dataBase->query($query);
        $row_count_transporters = $this->dataBase->numRows($result);
        while ($row = $this->dataBase->fetchArray($result)) {
            /* $result_Balance = $this->getBalance($row['id']);
                $row['balance'] = $result_Balance; */
            array_push($transporters, $row);
        }

        return array("transporters_list" => $transporters, "NumberOfTransporters" => $row_count_transporters);
    }

    public function getClientPersonalInfo($clientId)
    {
        $client_query = "SELECT ct.FirstName, ct.LastName, ct.Email, ct.IdClient as IDNumber, ct.togo_share_value, cust.PhoneNumber as phone, cust.IsBlocked, cust.OdooId, cbt.LogoUrl as img 
        FROM togo.customer as cust 
        inner join togo.clienttable as ct 
        inner join togo.clientbusinesstable as cbt on cust.id=ct.CustomerId and cust.id=cbt.CustomerId 
        where cust.id='$clientId'";
        $result_query = $this->dataBase->query($client_query);
        $res_arr = $this->dataBase->fetchArray($result_query);

        require_once('OdooService.php');
        $odooService = new OdooService();
        $result_Balance = $odooService->getBalance($clientId);

        $res_arr['balance'] = $result_Balance;

        return array("server_response" => $res_arr);
    }

    public function getClientBusinessInfo($clientId)
    {
        $client_query = "SELECT BusinessName, BusinessPlace, BusinessType FROM togo.clientbusinesstable as cbt where cbt.CustomerId='$clientId'";
        $result_query = $this->dataBase->query($client_query);
        $res_arr = $this->dataBase->fetchArray($result_query);

        return array("server_response" => $res_arr);
    }

    public function updateClientPersonalInfo(
        $infoArr,
        $personalImageCode,
        $personalImageName,
        $isPersonalImageUpdated,
        $isNewPersonalImage
    ) {
        $pesonalInfoMessage = false;
        $pesonalImageMessage = false;

        /* 
                update personal info
            */

        // id, firstName, lastName, email, idNumber
        $infoArr = explode(",", $infoArr);

        $query1 = "update togo.clienttable set FirstName='$infoArr[1]', LastName='$infoArr[2]', Email='$infoArr[3]', IdClient='$infoArr[4]', togo_share_value='$infoArr[5]' where CustomerId='$infoArr[0]'";

        $result1 = $this->dataBase->query($query1);

        if ($result1) {
            // echo "Updated Successfully";
            $pesonalInfoMessage = true;
        } else {
            // echo "Something wnet wrong!";
            $pesonalInfoMessage = false;
        }

        // -----------------------------------------------------

        /* 
                update personal image if a new image uploaded
            */

        // echo "image name: " . $personalImageName . " ------- is image updated? " . $isPersonalImageUpdated . " ------- is it a new image? " . $isNewPersonalImage . " ------- image code: " . $personalImageCode . " :::";

        if ($isPersonalImageUpdated == "true") {
            // check if image content data is valid
            if (explode(':', $personalImageCode)[0] == 'data') {

                // get only the incoded data to decode
                $data = explode(',', $personalImageCode);
                $decoded_string_PersonalImg = base64_decode($data[1]);

                // if there is no previous personal image for this client
                if ($isNewPersonalImage == "true") {

                    // add the new image name to the database
                    $query2 = "update togo.clientbusinesstable set LogoUrl='$personalImageName' where CustomerId='$infoArr[0]'";
                    $result2 = $this->dataBase->query($query2);

                    if ($result2) {
                        // add the new image file to the server
                        $path_Personal = '../' . $personalImageName;
                        $file_Personal = fopen($path_Personal, 'wb');

                        $is_written_Personal = fwrite($file_Personal, $decoded_string_PersonalImg);

                        fclose($file_Personal);

                        if ($is_written_Personal > 0) {
                            $pesonalImageMessage = true;
                            // echo "Personal Image Uploaded Successfully";
                        } else {
                            $pesonalImageMessage = false;
                            // echo "personalImageUploadError!";
                        }
                    } else {
                        $pesonalImageMessage = false;
                    }
                } else { // if there is a previous image

                    // clear previous image file data
                    file_put_contents('../' . $personalImageName, "");

                    // add new image data to the previous image file
                    $path_Personal = '../' . $personalImageName;
                    $file_Personal = fopen($path_Personal, 'wb');

                    $is_written_Personal = fwrite($file_Personal, $decoded_string_PersonalImg);

                    fclose($file_Personal);

                    if ($is_written_Personal > 0) {
                        $pesonalImageMessage = true;
                        // echo "Personal Image Uploaded Successfully";
                    } else {
                        $pesonalImageMessage = false;
                        // echo "personalImageUploadError!";
                    }
                }
            } else {
                $pesonalImageMessage = false;
                // echo "personalImageDataError";
            }
        }

        if ($isPersonalImageUpdated == "true") {
            if ($pesonalInfoMessage && $pesonalImageMessage) {
                echo "All Data Updated Successfully";
            } else {
                echo "An error occurred while updating personal info or personal image!";
            }
        } else {
            if ($pesonalInfoMessage) {
                echo "Personal Info Updated Successfully";
            } else {
                echo "An error occurred while updating personal info !";
            }
        }
    }

    public function updateClientBusinessInfo($info)
    {
        $businessInfoMessage = false;

        $infoArr = explode(",", $info);

        $query1 = "update togo.clientbusinesstable set BusinessName='$infoArr[1]', BusinessPlace='$infoArr[2]' where CustomerId='$infoArr[0]'";

        $result1 = $this->dataBase->query($query1);

        if ($result1) {
            // echo "Updated Successfully";
            $businessInfoMessage = true;
        } else {
            // echo "Something wnet wrong!";
            $businessInfoMessage = false;
        }

        if ($businessInfoMessage == "true") {
            echo "Business info updated successfully";
        } else {
            echo "business info update error!";
        }
    }

    public function blockUser($customerId, $status)
    {
        $statusNum = 0;
        if ($status == "true") {
            $statusNum = 1;
        } else {
            $statusNum = 0;
        }

        $query = "update togo.customer set IsBlocked=$statusNum where id='$customerId'";
        $result = $this->dataBase->query($query);

        if ($result) {
            if ($statusNum == 1) {
                echo "User Blocked";
            } else {
                echo "User Unblocked";
            }
        } else {
            echo "An error occurred!";
        }
    }

    public function getClientTotalOrdersNum($clientId)
    {
        $query = "select count(*) as ordersCount from togo.orderbidengin where CustomerId='$clientId'";
        $result = $this->dataBase->query($query);
        $row = $this->dataBase->fetchArray($result);

        return array("ordersNum" => $row['ordersCount']);
    }

    public function getTransporterPersonalInfo($transporterId)
    {
        $transporter_query = "SELECT FirstName, LastName, IDPlace, PersonalImgPath as img, customer.PhoneNumber as phone, customer.IsBlocked, customer.OdooId, IDNumber, LicenceNumber, Email, AccountName, LicenceImgPath 
        FROM togo.transportertable as transportertable
        inner join togo.customer as customer on customer.id='$transporterId'  
        where customerId='$transporterId'";
        $result_query = $this->dataBase->query($transporter_query);
        $res_arr = $this->dataBase->fetchArray($result_query);

        $id_Place = $res_arr['IDPlace'];
        $query_GetNamePlace = "Select * from togo.IDPlaceLanguage Where IdPlace='$id_Place'";

        $result_GetPlace = $this->dataBase->query($query_GetNamePlace);
        $row_Place = $this->dataBase->fetchArray($result_GetPlace);
        $NamePlace = $row_Place['NamePlace'];

        require_once('OdooService.php');
        $odooService = new OdooService();
        $result_Balance = $odooService->getBalance($transporterId);

        $res_arr['balance'] = $result_Balance;

        return array("server_response" => $res_arr);
    }

    public function getTransporterWorkingTimes($TransporterId)
    {
        $TimeArray = array();

        $query_GetTime = "Select * from togo.WorkDaysTime Where CustomerId='$TransporterId'";

        $Result_Time = $this->dataBase->query($query_GetTime);

        while ($row = $this->dataBase->fetchArray($Result_Time)) {
            array_push($TimeArray, array(
                "IdTime" => $row['id'], "SatTimeStart" => $row['SatTimeStart'],
                "SatTimeFinish" => $row['SatTimeFinish'], "SunTimeStart" => $row['SunTimeStart'],
                "SunTimeFinish" => $row['SunTimeFinish'], "MonTimeStart" => $row['MonTimeStart'],
                "MonTimeFinish" => $row['MonTimeFinish'], "TueTimeStart" => $row['TueTimeStart'],
                "TueTimeFinish" => $row['TueTimeFinish'], "WenTimeStart" => $row['WenTimeStart'],
                "WenTimeFinish" => $row['WenTimeFinish'], "ThuTimeStart" => $row['ThuTimeStart'],
                "ThuTimeFinish" => $row['ThuTimeFinish'], "FriTimeStart" => $row['FriTimeStart'],
                "FriTimeFinish" => $row['FriTimeFinish']
            ));
        }
        return array("TimeResponse" => $TimeArray);
    }

    public function getTransporterBusinessLocation($TransporterId)
    {
        $CityArray = array();

        $data = array();
        $IdPlaceArray = array();

        $query_get_Lang = "Select * from togo.Customer Where id='$TransporterId'";
        $result_Get_Lang = $this->dataBase->query($query_get_Lang);
        $row_Get_Lang = $this->dataBase->fetchArray($result_Get_Lang);
        $RegionId = $row_Get_Lang['RegionId'];

        $query_GetCits = "Select name As CityName ,cityId As id from togo.citylang where languageId=1 order by CityName";
        $result_getCites = $this->dataBase->query($query_GetCits);


        $query_GetAll_City_Transporter = "Select * from togo.TransporterWorkCity where CustomerId='$TransporterId'";
        $result_City_Trans = $this->dataBase->query($query_GetAll_City_Transporter);

        while ($row_Get_TranspCity = $this->dataBase->fetchArray($result_City_Trans)) {
            $CityId = $row_Get_TranspCity['CityId'];
            $data[] = $CityId;
        }


        while ($row_Get_Lang = $this->dataBase->fetchArray($result_getCites)) {
            $NammeCity = $row_Get_Lang['CityName'];
            $id_City = $row_Get_Lang['id'];


            $query_Check_addedd = "Select * from togo.TransporterWorkCity Where CustomerId='$TransporterId' AND CityId='$id_City' AND deleted=0";
            $result_Check_Addedd = $this->dataBase->query($query_Check_addedd);
            $numRows_Check = $this->dataBase->numRows($result_Check_Addedd);


            if ($numRows_Check > 0)
                array_push($CityArray, array("IdCity" => $id_City, "CityName" => $NammeCity, "CheckAdded" => "Added"));

            else
                array_push($CityArray, array("IdCity" => $id_City, "CityName" => $NammeCity, "CheckAdded" => "NotAddedd"));
        }

        return array("CityResponse" => $CityArray);
    }

    public function GetTransporterVehiclesInfo($TransporterId)
    {
        $CarArray = array();
        $query = "Select VehicleNameLang.Name as VehicleName,VehicleNameLang.IdVehicle as vehicleid,TransporterCarInfo.LicenceCarNumber as LicenceNumber, TransporterCarInfo.RegistrationImgPath, TransporterCarInfo.CarImgPath, TransporterCarInfo.RegistrationNumber, TransporterCarInfo.RegistrationFinshDay 
        from togo.TransporterCarInfo as TransporterCarInfo, 
        togo.VehicleNameLang as VehicleNameLang 
        Where TransporterCarInfo.CarImgId = VehicleNameLang.IdVehicle AND CustomerId ='$TransporterId' AND VehicleNameLang.IdLanguage=1";
        $result = $this->dataBase->query($query);
        if ($this->dataBase->numRows($result) > 0) {
            while ($row = $this->dataBase->fetchArray($result)) {
                array_push($CarArray, array("LicenceNumber" => $row['LicenceNumber'], "Name" => $row['VehicleName'], "RegistrationImgPath" => $row['RegistrationImgPath'], "CarImgPath" => $row['CarImgPath'], "RegistrationNumber" => $row['RegistrationNumber'], "RegistrationFinshDay" => $row['RegistrationFinshDay']));
            }

            $query = "Select transporter_delivery_types.type from togo.transporter_delivery_types as transporter_delivery_types where transporter_id='$TransporterId'";
            $result = $this->dataBase->query($query);

            $typesArray = array();
            if ($this->dataBase->numRows($result) > 0) {
                while ($row = $this->dataBase->fetchArray($result)) {
                    array_push($typesArray, $row['type']);
                }
            }

            return array("server_response" => $CarArray, "types" => $typesArray);
        } else {
            return array("server_response" => "No Info Found");
        }
    }

    public function updateTransporterPersonalInfo(
        $infoArr,
        $personalImageCode,
        $personalImageName,
        $isPersonalImageUpdated,
        $isNewPersonalImage,
        $licenceImageCode,
        $licenceImageName,
        $isLicenceImageUpdated,
        $isNewLicenceImage
    ) {
        // echo "image name: " . $personalImageName . " ------- is image updated? " . $isPersonalImageUpdated . " ------- is it a new image? " . $isNewPersonalImage . " ------- image code: " . $personalImageCode . " :::";
        // echo "image name: " . $licenceImageName . " ------- is image updated? " . $isLicenceImageUpdated . " ------- is it a new image? " . $isNewLicenceImage . " ------- image code: " . $licenceImageCode . " :::";

        $pesonalInfoMessage = false;
        $pesonalImageMessage = false;
        $licenceImageMessage = false;

        /* 
                update personal info
            */

        $infoArr = explode(",", $infoArr);

        $query1 = "update togo.transportertable set FirstName='$infoArr[1]', LastName='$infoArr[2]', AccountName='$infoArr[3]', Email='$infoArr[4]', IDNumber='$infoArr[5]', LicenceNumber='$infoArr[6]' where CustomerId='$infoArr[0]'";

        $result1 = $this->dataBase->query($query1);

        if ($result1) {
            // echo "Personal Info Updated Successfully";
            $pesonalInfoMessage = true;
        } else {
            // echo "personalInfoError!";
            $pesonalInfoMessage = false;
        }

        /* 
                update personal image if a new image uploaded
            */
        if ($isPersonalImageUpdated == "true") {
            // check if image content data is valid
            if (explode(':', $personalImageCode)[0] == 'data') {

                // get only the incoded data to decode
                $data = explode(',', $personalImageCode);
                $decoded_string_PersonalImg = base64_decode($data[1]);

                // if there is no previous personal image for this transporter
                if ($isNewPersonalImage == "true") {

                    // add the new image name to the database
                    $query2 = "update togo.transportertable set PersonalImgPath='$personalImageName' where CustomerId='$infoArr[0]'";
                    $result2 = $this->dataBase->query($query2);

                    if ($result2) {
                        // add the new image file to the server
                        $path_Personal = '../' . $personalImageName;
                        $file_Personal = fopen($path_Personal, 'wb');

                        $is_written_Personal = fwrite($file_Personal, $decoded_string_PersonalImg);

                        fclose($file_Personal);

                        if ($is_written_Personal > 0) {
                            $pesonalImageMessage = true;
                            // echo "Personal Image Uploaded Successfully";
                        } else {
                            $pesonalImageMessage = false;
                            // echo "personalImageUploadError!";
                        }
                    } else {
                        $pesonalImageMessage = false;
                    }
                } else { // if there is a previous image

                    // clear previous image file data
                    file_put_contents('../' . $personalImageName, "");

                    // add new image data to the previous image file
                    $path_Personal = '../' . $personalImageName;
                    $file_Personal = fopen($path_Personal, 'wb');

                    $is_written_Personal = fwrite($file_Personal, $decoded_string_PersonalImg);

                    fclose($file_Personal);

                    if ($is_written_Personal > 0) {
                        $pesonalImageMessage = true;
                        // echo "Personal Image Uploaded Successfully";
                    } else {
                        $pesonalImageMessage = false;
                        // echo "personalImageUploadError!";
                    }
                }
            } else {
                $pesonalImageMessage = false;
                // echo "personalImageDataError";
            }
        }

        /* 
                update licence image if a new image uploaded
            */
        if ($isLicenceImageUpdated == "true") {
            // check if image content data is valid
            if (explode(':', $licenceImageCode)[0] == 'data') {

                // get only the incoded data to decode
                $data = explode(',', $licenceImageCode);
                $decoded_string_LicenceImg = base64_decode($data[1]);

                // if there is no previous licence image for this transporter
                if ($isNewLicenceImage == "true") {

                    // add the new image name to the database
                    $query2 = "update togo.transportertable set LicenceImgPath='$licenceImageName' where CustomerId='$infoArr[0]'";
                    $result2 = $this->dataBase->query($query2);

                    if ($result2) {
                        // add the new image file to the server
                        $path_Licence = '../' . $licenceImageName;
                        $file_Licence = fopen($path_Licence, 'wb');

                        $is_written_Licence = fwrite($file_Licence, $decoded_string_LicenceImg);

                        fclose($file_Licence);

                        if ($is_written_Licence > 0) {
                            $licenceImageMessage = true;
                            // echo "Licence Image Uploaded Successfully";
                        } else {
                            $licenceImageMessage = false;
                            // echo "licenceImageUploadError!";
                        }
                    } else {
                        $licenceImageMessage = false;
                    }
                } else { // if there is a previous image

                    // clear previous image file data
                    file_put_contents('../' . $licenceImageName, "");

                    // add new image data to the previous image file
                    $path_Licence = '../' . $licenceImageName;
                    $file_Licence = fopen($path_Licence, 'wb');

                    $is_written_Licence = fwrite($file_Licence, $decoded_string_LicenceImg);

                    fclose($file_Licence);

                    if ($is_written_Licence > 0) {
                        $licenceImageMessage = true;
                        // echo "Licence Image Uploaded Successfully";
                    } else {
                        $licenceImageMessage = false;
                        // echo "licenceImageUploadError!";
                    }
                }
            } else {
                $licenceImageMessage = false;
                // echo "licenceImageDataError";
            }
        }

        if ($isPersonalImageUpdated == "true" && $isLicenceImageUpdated == "true") {
            if ($pesonalInfoMessage && $pesonalImageMessage && $licenceImageMessage) {
                echo "All Data Updated Successfully";
            } else {
                echo "An error occurred!";
            }
        } else if ($isPersonalImageUpdated == "false" && $isLicenceImageUpdated == "true") {
            if ($pesonalInfoMessage && $licenceImageMessage) {
                echo "All Data Updated Successfully";
            } else {
                echo "An error occurred while updateing personal info or licence image!";
            }
        } else if ($isPersonalImageUpdated == "true" && $isLicenceImageUpdated == "false") {
            if ($pesonalInfoMessage && $pesonalImageMessage) {
                echo "All Data Updated Successfully";
            } else {
                echo "An error occurred while updateing personal info or personal image!";
            }
        } else {
            if ($pesonalInfoMessage) {
                echo "Personal Info Updated Successfully";
            } else {
                echo "An error occurred while updating personal info !";
            }
        }
    }

    public function updateTransporterBusinessLocations($cityId, $checked, $transporterId)
    {
        $query_Check_addedd = "Select * from togo.transporterworkcity Where CustomerId='$transporterId' AND CityId='$cityId'";
        $result_Check_Addedd = $this->dataBase->query($query_Check_addedd);
        $numRows_Check = $this->dataBase->numRows($result_Check_Addedd);

        if ($numRows_Check > 0) {
            $row = $this->dataBase->fetchArray($result_Check_Addedd);
            $query_Update_Add = "Update togo.transporterworkcity set deleted=0 where CustomerId='$transporterId' AND CityId='$cityId'";
            $query_Update_Remove = "Update togo.transporterworkcity set deleted=1 where CustomerId='$transporterId' AND CityId='$cityId'";
            if ($checked == 1) {

                if ($row['deleted'] == 1) {
                    $result = $this->dataBase->query($query_Update_Add);
                    if ($result == true) {
                        echo "UpdatedAddSucessfully";
                    } else {
                        echo "NotUpdated";
                    }
                } else {
                    echo "NotUpdated";
                }
            } else if ($checked == 0) {
                if ($row['deleted'] == 0) {

                    $result = $this->dataBase->query($query_Update_Remove);
                    if ($result == true) {
                        echo "UpdatedRemoveSucessfully";
                    } else {
                        echo "NotUpdated";
                    }
                } else {
                    echo "NotUpdated";
                }
            }
        } else {
            if ($checked == 1) {
                $query_add_City = "Insert into togo.transporterworkcity (CustomerId,CityId) Values('$transporterId','$cityId')";
                $result_added = $this->dataBase->query($query_add_City);

                if ($result_added == true) {
                    echo "AddeddSucessfully";
                } else {
                    echo "NotAddedd";
                }
            } else {
                echo "AlreadyRemoved";
            }
        }
    }

    public function getTransporterCitiesPricesForAdmin($transporterId)
    {
        $query_getCities = "select id, name from togo.citytable";
        $result_getCities = $this->dataBase->query($query_getCities);

        $citiesArr = array();

        if ($result_getCities) {
            while ($row_getCities = $this->dataBase->fetchArray($result_getCities)) {
                array_push($citiesArr, array("id" => $row_getCities['id'], "name" => $row_getCities['name']));
            }
        } else {
            echo "getCitiesError";
            return;
        }

        $pricesArr = array();

        for ($c = 0; $c < count($citiesArr); $c++) {

            $tempCityId = $citiesArr[$c]['id'];
            $query_getPricesPerCity = "select * from togo.roadpricestable" . $tempCityId . " where transporterId = '$transporterId'";
            $result_getPricesPerCity = $this->dataBase->query($query_getPricesPerCity);
            $row_getPricesPerCity = $this->dataBase->fetchArray($result_getPricesPerCity);

            $i = 0;
            foreach ($row_getPricesPerCity as $key => $value) {
                if ($i >= 5) {
                    $fromId = explode("_", $key)[0];
                    $toId = explode("_", $key)[1];
                    $price = $value;

                    $query_getFromCityName = "select name from togo.citylang where cityId = '$fromId' and languageId = 1";
                    $query_getToCityName = "select name from togo.citylang where cityId = '$toId' and languageId = 1";
                    $result_getFromCityName = $this->dataBase->query($query_getFromCityName);
                    $result_getToCityName = $this->dataBase->query($query_getToCityName);
                    $row_getFromCityName = $this->dataBase->fetchArray($result_getFromCityName);
                    $row_getToCityName = $this->dataBase->fetchArray($result_getToCityName);

                    array_push($pricesArr, array("transporterId" => $row_getPricesPerCity['transporterId'], "fromId" => $fromId, "fromName" => $row_getFromCityName['name'], "toId" => $toId, "toName" => $row_getToCityName['name'], "price" => $price));
                }
                $i++;
            }
        }

        return array("response" => $pricesArr);
    }

    public function updateTransporterCitiesPricesForAdmin($transporterId, $fromId, $toId, $price)
    {
        $query = "update togo.`roadpricestable" . $fromId . "` set `" . $fromId . "_" . $toId . "`='$price' where transporterId='$transporterId'";
        $result = $this->dataBase->query($query);

        if ($result) {
            echo "updated";
        } else {
            echo "error";
        }
    }

    public function getTransporterTotalOrdersNum($transporterId)
    {
        $query = "select count(distinct order_id) as ordersCount from togo.transporterstimelinetb where transporter_id = '$transporterId'";
        $result = $this->dataBase->query($query);
        $row = $this->dataBase->fetchArray($result);

        return array("ordersNum" => $row['ordersCount']);
    }

    public function getAllOrders($searchStr)
    {
        $query = "SELECT (select count(deliveryacceptordertable.IdOrder) from togo.deliveryacceptordertable as deliveryacceptordertable 
            where deliveryacceptordertable.IdOrder = orderbidengin.id) as bidsCount, receiverAddress.name as receiverName, orderbidengin.CostLoad,
            orderbidengin.TypeLoad, orderbidengin.DateLoad, orderbidengin.id, orderbidengin.order_status, clientbusinesstable.BusinessName as clientBusinessName, transportertable.AccountName as transporterAccountName,
            orderbidaddress.OtherDetails as fromAddress, fromCityRegion.name as fromCity, toCityRegion.name as toCity, orderbidaddress.OtherDetailsDes as toAddress
            From togo.orderbidengin as orderbidengin
            inner join togo.orderbidaddress as orderbidaddress On orderbidengin.id = orderbidaddress.idorderbidengin
            left outer join togo.clientbusinesstable as clientbusinesstable on orderbidengin.CustomerId = clientbusinesstable.CustomerId
            left outer join togo.transportertable as transportertable on orderbidengin.DeliveryId = transportertable.CustomerId
            left outer join togo.citylang as fromCityRegion on orderbidaddress.IdCity = fromCityRegion.cityId
            left outer join togo.citylang as toCityRegion on orderbidaddress.IdCityDes = toCityRegion.cityId
            left outer join togo.addresses as receiverAddress on orderbidaddress.ReciverAddressId = receiverAddress.id
            where (orderbidengin.id like '%" . $searchStr . "%' 
            or fromCityRegion.name like '%" . $searchStr . "%' 
            or toCityRegion.name like '%" . $searchStr . "%'
            or receiverAddress.name like '%" . $searchStr . "%'
            or clientbusinesstable.BusinessName like '%" . $searchStr . "%'
            or orderbidengin.order_status like '%" . $searchStr . "%')
            AND fromCityRegion.languageId = '1' AND toCityRegion.languageId = '1'
            order by orderbidengin.id desc";

        $orders = array();
        $result = $this->dataBase->query($query);
        $row_count_orders = $this->dataBase->numRows($result);

        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($orders, $row);
        }

        return array("orders_list" => $orders, "NumberOfOrders" => $row_count_orders);
    }

    public function getCustomersWithdrawRequestsForAdmin()
    {
        $query_getRequests = "select * from togo.withdrawrequeststb order by time_requested desc";
        $result_getRequests = $this->dataBase->query($query_getRequests);

        if ($result_getRequests) {

            $requests = array();

            while ($row_getRequests = $this->dataBase->fetchArray($result_getRequests)) {
                array_push($requests, array("ref" => $row_getRequests['ref'], "id" => $row_getRequests['id'], "customerName" => $row_getRequests['customerName'], "requestTime" => $row_getRequests['time_requested'], "amount" => $row_getRequests['amount'], "isCanceled" => $row_getRequests['isCanceled'], "isApproved" => $row_getRequests['isApproved'], "isRejected" => $row_getRequests['isRejected']));
            }

            return array("server_response" => $requests);
        } else {
            return "error fetching withdrawal requests";
        }
    }

    public function tempRegisterAdmin()
    {
        if (false) {
            $hashed_pass = password_hash('hussam123', PASSWORD_DEFAULT);
            // $hashed_token = password_hash('admin123456', PASSWORD_DEFAULT);

            $query_register_admin = "insert into togo.admin (FirstName, LastName, Email, MobileNumber, Username, Password) values ('Hussam', 'Bseiso', '', '', 'hussam', '$hashed_pass')";
            $result_register_admin = $this->dataBase->query($query_register_admin);

            if ($result_register_admin) {
                echo "regestered successfully!";
            } else {
                echo "an error occurred!";
            }
        }
    }

    public function transactionsByOrder($customerId, $orderId)
    {
        $customerId = intval($customerId);
        $orderId = intval($orderId);

        require_once('OdooService.php');

        $odooApi = new OdooService_2();

        $params = json_encode(array(
            "jsonrpc" => "2.0",
            "params" => array("db" => "ToGo", "login" => "admin", "password" => "admin")
        ));
        $data = $odooApi->callOdooUrl("/web/session/authenticate", "POST", $params);

        $params = json_encode(array("jsonrpc" => "2.0", "params" => array("customer_id" => $customerId, "order_id" => $orderId)));
        $data = $odooApi->callOdooUrl("/partner_order/entries", "POST", $params);

        return array("server_response" => $data);
    }
}
