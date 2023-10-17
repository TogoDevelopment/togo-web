<?php

class FoodAppService
{

    private $dataBase;

    /**
     * FoodAppService constructor.
     * @param $dataBase
     */
    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    // functions goes here ...

    // ------------------------- (login functions) -------------------------

    /* old login will be called from Apis.php */

    // send verification code
    public function sendVerificationCode($phoneNumber)
    {
        $phoneNumber = $this->dataBase->escape($phoneNumber);

        $Code_Verify = mt_rand(1000, 9999);
        $mobile = $phoneNumber;
        $msg = "ToGo Cod Is: " . $Code_Verify;

        $res = $this->sendSMS($mobile, $msg);

        if ($res == "message not sent") {
            echo " - (sendVerificationCode) message not sent - ";
        } else {
            echo " - (sendVerificationCode) message sent successfully - ";

            // insert new customer record (store verification code)

            $query_recordCode = "INSERT INTO togo.verifycodestable (code, mobile, description) VALUES ('$Code_Verify', '$mobile', 'Sign Up')";
            $result_recordCode = $this->dataBase->query($query_recordCode);

            if ($result_recordCode) {
                echo " - (sendVerificationCode) code inserted - ";
            } else {
                echo " - (sendVerificationCode) code insert error - ";
            }
        }
    }

    // register user
    // get all citites & areas (for now: محافظة رام الله و البيرة)
    public function getCitiesAndAreas($langId)
    {
        // get all cities (in محافظة رام الله و البيرة)
        $query_get_cities = "SELECT citylang.name, citylang.cityId FROM togo.citytable INNER JOIN togo.citylang ON citytable.id = citylang.cityId AND citylang.languageId='$langId' WHERE citytable.governorateId=1";
        $result_get_cities = $this->dataBase->query($query_get_cities);

        if ($result_get_cities) {

            $citiesArray = array();
            while ($row_get_cities = $this->dataBase->fetchArray($result_get_cities)) {
                array_push($citiesArray, array("cityName" => $row_get_cities['name'], "cityId" => $row_get_cities['cityId']));
            }

            // get all areas (in محافظة رام الله و البيرة)
            $query_get_areas = "SELECT arealang.name, arealang.areaId FROM togo.citytable RIGHT OUTER JOIN togo.areatable ON citytable.id = areatable.cityId RIGHT OUTER JOIN togo.arealang ON areatable.id = arealang.areaId AND arealang.languageId='$langId' WHERE citytable.governorateId=1";
            $result_get_areas = $this->dataBase->query($query_get_areas);

            if ($result_get_areas) {

                $areasArray = array();
                while ($row_get_areas = $this->dataBase->fetchArray($result_get_areas)) {
                    array_push($areasArray, array("areaName" => $row_get_areas['name'], "areaId" => $row_get_areas['areaId']));
                }

                echo json_encode(array("citiesList" => $citiesArray, "areasList" => $areasArray));
            } else {
                echo " - (getCitiesAndAreas) get areas query error - ";
            }
        } else {
            echo " - (getCitiesAndAreas) get cities query error - ";
        }
    }

    // register user
    public function registerUser($userInfo, $verificationCode)
    {
        $userInfo = $this->dataBase->escape($userInfo);
        $verificationCode = $this->dataBase->escape($verificationCode);

        // $userInfo contains all the sign-up info
        $userInfo = explode(",", $userInfo);

        $phoneNumber = strval($userInfo[1]); // to be edited

        // get verification code from "customer" table
        $query_get_verification_code = "SELECT code FROM togo.verifycodestable WHERE mobile='$phoneNumber' ORDER BY time_stamp DESC LIMIT 1";
        $result_get_verification_code = $this->dataBase->query($query_get_verification_code);

        if ($result_get_verification_code) {
            $row_get_verification_code = $this->dataBase->fetchArray($result_get_verification_code);

            $fetchedCode = $row_get_verification_code['code'];

            if ($fetchedCode == $verificationCode) {
                // codes match -> register

                $userType = strval($userInfo[0]); // to be edited

                if ($userType == 'client') {

                    // initiate the parameters from $userInfo
                    $name = strval($userInfo[2]);
                    $phone = $phoneNumber;
                    $email = strval($userInfo[3]);
                    $imageName = strval($userInfo[4]);
                    $imageCode = strval($userInfo[5]);
                    $address = strval($userInfo[6]);
                    $area = strval($userInfo[7]);
                    $langId = strval($userInfo[8]);

                    /*  echo " -- name: " . $name . ", phone: " . $phone . ", email: " . $email . ", imageName: " . $imageName . ", address: " . $address . ", area: " . $area . ", langId: " . $langId . " -- ";
                    return; */

                    $this->registerClient($name, $phone, $email, $imageName, $imageCode, $address, $area, $langId, $verificationCode);
                } else if ($userType == 'transporter') {

                    // initiate the parameters from $userInfo
                    $name = strval($userInfo[2]);
                    $phone = $phoneNumber;
                    $email = strval($userInfo[3]);
                    $imageName = strval($userInfo[4]);
                    $imageCode = strval($userInfo[5]);
                    $ID = strval($userInfo[6]);
                    $areasOfOperation = $userInfo[7];
                    $langId = strval($userInfo[8]);

                    $this->registerTransporter($name, $phone, $email, $imageName, $imageCode, $ID, $areasOfOperation, $langId, $verificationCode);
                } else {
                    echo " - (registerUser) user type unknown - ";
                }
            } else {
                echo " - (registerUser) wrong code - ";
            }
        } else {
            echo " - (registerUser) get verification code query error - ";
        }
    }

    // register client
    public function registerClient($name, $phone, $email, $imageName, $imageCode, $address, $area, $langId, $verificationCode)
    {
        // insert into customer
        $query_insert_customer = "INSERT INTO togo.customer (PhoneNumber, IsAccepted, IsVerified, IsClient, isFoodClient, VerifiedKey, LanguageId) 
                                    VALUES ('$phone', 1, 1, 1, 1, '$verificationCode', '$langId')";
        $result_insert_customer = $this->dataBase->query($query_insert_customer);

        if ($result_insert_customer) {
            // get customer id
            $query_get_customer_id = "SELECT id FROM togo.customer WHERE PhoneNumber='$phone'";
            $result_get_customer_id = $this->dataBase->query($query_get_customer_id);

            if ($result_get_customer_id) {
                $row_get_customer_id = $this->dataBase->fetchArray($result_get_customer_id);
                $customerId = $row_get_customer_id['id'];

                // create Odoo partner
                require_once('OdooService.php');
                $odooService = new OdooService();
                $odooId = $odooService->createNewPartner($customerId, $phone, 'Client', '--', $email, $name);

                // update odoo id
                $query_update_odoo_id = "UPDATE togo.customer SET OdooId='$odooId' WHERE id = '$customerId'";
                $result_update_odoo_id = $this->dataBase->query($query_update_odoo_id);

                if ($result_update_odoo_id) {

                    // insert into clienttable
                    $query_insert_clientTable = "INSERT INTO togo.clienttable (CustomerId, Email) VALUES ('$customerId', '$email')";
                    $result_insert_clientTable = $this->dataBase->query($query_insert_clientTable);

                    if ($result_insert_clientTable) {
                        // upload image
                        $decoded_string = base64_decode($imageCode);
                        $path = '../img/BusinessLogo/' . $imageName;
                        $ImageName = 'img/BusinessLogo/' . $imageName;

                        $file = fopen($path, 'wb');
                        $is_written = fwrite($file, $decoded_string);
                        fclose($file);

                        if ($is_written > 0) {
                            // insert into clientbusinesstable
                            $query_insert_clientbusinesstable = "INSERT INTO togo.clientbusinesstable (CustomerId, BusinessName, BusinessPlace, cityId, LogoUrl) 
                                                                        VALUES ('$customerId', '$name', '$address', '$area', '$ImageName')";
                            $result_insert_clientbusinesstable = $this->dataBase->query($query_insert_clientbusinesstable);

                            if ($result_insert_clientbusinesstable) {

                                echo " - (registerClient) register done successfully - ";
                            } else {
                                echo " - (registerClient) insert clientbusinesstable query error - ";
                            }
                        } else {
                            echo " - (registerClient) upload image error - ";
                        }
                    } else {
                        echo " - (registerClient) insert clienttable query error - ";
                    }
                } else {
                    echo " - (registerClient) update odoo id query error - ";
                }
            } else {
                echo " - (registerClient) get customer id query error - ";
            }
        } else {
            echo " - (registerClient) insert customer query error - ";
        }
    }

    // register transporter
    public function registerTransporter($name, $phone, $email, $imageName, $imageCode, $ID, $areasOfOperation, $langId, $verificationCode)
    {
        // insert into customer
        // insert into transportertable
        echo "registerTransporter";
    }

    // ------------------------- (client functions) -------------------------

    // main
    // personal info including wallet
    public function getClientMainPersonalInfo($clientCustomerId)
    {
        // get client's name, logo
        $query_get_info = "SELECT BusinessName AS name, LogoUrl AS image FROM togo.clientbusinesstable WHERE CustomerId='$clientCustomerId'";
        $result_get_info = $this->dataBase->query($query_get_info);

        if ($result_get_info) {

            $row_get_info = $this->dataBase->fetchArray($result_get_info);
            $name = $row_get_info['name'];
            $image = $row_get_info['image'];

            // get client's balance
            require_once('OdooService.php');
            $odooService = new OdooService();
            $balance = $odooService->getBalance($clientCustomerId);

            return array("name" => $name, "image" => $image, "balance" => $balance);
        } else {
            echo " - (getClientMainPersonalInfo) get client info query error - ";
        }
    }

    // main
    public function getAllNewOrdersCount($clientCustomerId)
    {
        // in the following case "Waiting for Bids" has nothing to do with the bid concept, it will be denoted as "New Order"
        // "TypeLoad = 1" is for food-orders, but it won't affect the query because the client with "CustomerId" will have only food-type-orders
        $query_get_orders_count = "SELECT count(*) AS ordersCount FROM togo.orderbidengin WHERE TypeLoad = 1 AND CustomerId = '$clientCustomerId' AND order_status='Waiting for Bids'";
        $result_get_orders_count = $this->dataBase->query($query_get_orders_count);

        if ($result_get_orders_count) {

            $row_get_orders_count = $this->dataBase->fetchArray($result_get_orders_count);
            $orders_count = $row_get_orders_count['ordersCount'];
            return $orders_count;
        } else {
            echo " - (getAllNewOrdersCount) get orders count query error - ";
        }
    }

    // main
    public function getAllActiveOrdersCount($clientCustomerId)
    {
        $query_get_orders_count = "SELECT count(*) AS ordersCount FROM togo.orderbidengin WHERE TypeLoad = 1 AND CustomerId = '$clientCustomerId' AND order_status='Out For Delivery'";
        $result_get_orders_count = $this->dataBase->query($query_get_orders_count);

        if ($result_get_orders_count) {

            $row_get_orders_count = $this->dataBase->fetchArray($result_get_orders_count);
            $orders_count = $row_get_orders_count['ordersCount'];
            return $orders_count;
        } else {
            echo " - (getAllActiveOrdersCount) get orders count query error - ";
        }
    }

    // main
    public function getAllFinishedOrdersCount($clientCustomerId)
    {
        $query_get_orders_count = "SELECT count(*) AS ordersCount FROM togo.orderbidengin WHERE TypeLoad = 1 AND CustomerId = '$clientCustomerId' AND order_status='Delivered'";
        $result_get_orders_count = $this->dataBase->query($query_get_orders_count);

        if ($result_get_orders_count) {

            $row_get_orders_count = $this->dataBase->fetchArray($result_get_orders_count);
            $orders_count = $row_get_orders_count['ordersCount'];
            return $orders_count;
        } else {
            echo " - (getAllFinishedOrdersCount) get orders count query error - ";
        }
    }

    // main 
    // food customers table -> to be created
    public function getCustomersCount($clientCustomerId)
    {
        $query_get_customers_count = "SELECT count(*) as customersCount FROM togo.addresses WHERE customer_id='$clientCustomerId'";
        $result_get_customers_count = $this->dataBase->query($query_get_customers_count);

        if ($result_get_customers_count) {

            $row_get_customers_count = $this->dataBase->fetchArray($result_get_customers_count);

            $customers_count = $row_get_customers_count['customersCount'];

            return array("status" => "success", "customers_count" => $customers_count);
        } else {
            return array("status" => "error", "error" => "(getCustomersCount) get_customers_count query error");
        }
    }

    // (add customer page) get restaurant areas to choose from when create new customer
    public function getClientAreas($clientCustomerId, $langId)
    {
        $query_get_areas = "SELECT * FROM togo.restaurantareas WHERE clientId = '$clientCustomerId' AND deleted = 0";
        $result_get_areas = $this->dataBase->query($query_get_areas);

        if ($result_get_areas) {

            $areas = array();
            while ($row_get_areas = $this->dataBase->fetchArray($result_get_areas)) {

                // get area name
                $areaId = $row_get_areas['areaId'];

                $query_get_area_name = "SELECT name FROM togo.arealang WHERE areaId = '$areaId' AND languageId = '$langId'";
                $result_get_area_name = $this->dataBase->query($query_get_area_name);

                if ($result_get_area_name) {

                    $row_get_area_name = $this->dataBase->fetchArray($result_get_area_name);

                    $area_name = $row_get_area_name['name'];

                    array_push($areas, array("areaId" => $row_get_areas['id'], "areaName" => $area_name, "price" => $row_get_areas['price'], "description" => $row_get_areas['description']));
                } else {
                    return array("status" => "error", "error" => "(getClientAreas) get_area_name query error");
                }
            }

            return array("status" => "success", "areas" => $areas);
        } else {
            return array("status" => "error", "error" => "(getClientAreas) query_get_areas query error");
        }
    }

    // add customer
    public function addCustomer($clientCustomerId, $phoneNumber, $areaId)
    {
        // get cityId, provId, and govId by areaId
        $query_get_ids = "SELECT citytable.id AS cityId, governoratetable.id AS govId, provincestable.id AS provId
                            FROM togo.areatable 
                            INNER JOIN togo.citytable ON areatable.cityId = citytable.id
                            INNER JOIN togo.governoratetable ON citytable.governorateId = governoratetable.id
                            INNER JOIN togo.provincestable ON governoratetable.provinceId = provincestable.id
                            WHERE areatable.id = '$areaId'";
        $result_get_ids = $this->dataBase->query($query_get_ids);

        if ($result_get_ids) {

            $row_get_ids = $this->dataBase->fetchArray($result_get_ids);
            $cityId = $row_get_ids['cityId'];
            $govId = $row_get_ids['govId'];
            $provId = $row_get_ids['provId'];

            // table addresses will be treated as "customers"
            $query_insert_new_customer = "INSERT INTO togo.addresses (phone_number, customer_id, creator_id, areaId, cityId, governoratId, provinceId) 
                                                        VALUES ('$phoneNumber', '$clientCustomerId', '$clientCustomerId', '$areaId', '$cityId', '$govId', '$provId')";
            $result_insert_new_customer = $this->dataBase->query($query_insert_new_customer);

            if ($result_insert_new_customer) {

                echo " - (addCustomer) customer added successfully - ";
            } else {
                echo " - (addCustomer) insert_new_customer query error - ";
            }
        } else {
            echo " - (addCustomer) get_ids query error - ";
        }
    }

    public function getCustomerInfo($clientCustomerId, $langId)
    {

        $query_get_customer_info = "SELECT name, phone_number, details, longitude, latitude, areaId FROM togo.addresses WHERE id = '$clientCustomerId'";
        $result_get_customer_info = $this->dataBase->query($query_get_customer_info);

        if ($result_get_customer_info) {

            $row_get_customer_info = $this->dataBase->fetchArray($result_get_customer_info);
            $areaId = $row_get_customer_info['areaId'];

            // get area name
            $query_get_area_name = "SELECT name FROM togo.arealang WHERE areaId = '$areaId' AND languageId = '$langId'";
            $result_get_area_name = $this->dataBase->query($query_get_area_name);

            if ($result_get_area_name) {

                $row_get_area_name = $this->dataBase->fetchArray($result_get_area_name);
                $areaName = $row_get_area_name['name'];

                $customer_info = array(
                    "customerName" => $row_get_customer_info['name'],
                    "customerPhone" => $row_get_customer_info['phone_number'],
                    "otherDetails" => $row_get_customer_info['details'],
                    "long" => $row_get_customer_info['longitude'],
                    "lat" => $row_get_customer_info['latitude'],
                    "areaId" => $areaId,
                    "areaName" => $areaName
                );
                return $customer_info;
            } else {
                echo " - (getCustomerInfo) get_area_name query error - ";
            }
        } else {
            echo " - (getCustomerInfo) get_customer_info query error - ";
        }
    }

    public function editCustomerInfo($clientCustomerId, $customerName, $customerPhone, $otherDetails, $areaId)
    {
        // when areaId is updated, its governorateId and provinceId will be updated, but for now, we are only working with governorete "محافظة رام الله و البيرة"

        // get cityId by areaId
        $query_get_city_id = "SELECT cityId FROM togo.areatable WHERE id = '$areaId'";
        $result_get_city_id = $this->dataBase->query($query_get_city_id);

        if (!$result_get_city_id) {
            echo " - (editCustomerInfo) get_city_id query error - ";
            return;
        }

        $num_rows_get_city_id = $this->dataBase->numRows($result_get_city_id);

        if ($num_rows_get_city_id == 0) {
            echo " - (editCustomerInfo) city not found for area [" . $areaId . "] error - ";
            return;
        }

        $query_update_customer_info = "UPDATE togo.addresses SET
                                        name='$customerName',
                                        phone_number='$customerPhone',
                                        details='$otherDetails',
                                        areaId='$areaId'
                                        WHERE id = '$clientCustomerId'
                                    ";
        $result_update_customer_info = $this->dataBase->query($query_update_customer_info);

        if (!$result_update_customer_info) {
            echo " - (editCustomerInfo) update_customer_info query error - ";
            return;
        }

        echo " - customer info updated successfully - ";
    }

    // new orders
    public function getAllNewOrders($clientCustomerId, $langId)
    {
        // in the following case "Waiting for Bids" has nothing to do with the bid concept, it will be denoted as "New Order"
        // "TypeLoad = 1" is for food-orders, but it won't affect the query because the client with "CustomerId" will have only food-type-orders
        $query_get_orders = "SELECT
                                engin.id AS orderId,
                                engin.CostLoad AS cod,
                                engin.createdAt AS dateCreated,
                                reciverAddress.name AS customerName,
                                reciverAddress.phone_number AS customerPhone,
                                reciverAddress.details AS otherDetails,
                                receiverArea.name AS customerArea
                                FROM togo.orderbidengin AS engin
                                INNER JOIN togo.orderbidaddress AS addresses ON engin.id = addresses.IdOrderBidEngin
                                LEFT OUTER JOIN togo.addresses AS reciverAddress ON addresses.ReciverAddressId = reciverAddress.id
                                LEFT OUTER JOIN togo.arealang AS receiverArea ON reciverAddress.areaId = receiverArea.areaId AND receiverArea.languageId = '$langId'
                                WHERE TypeLoad = 1 AND CustomerId = '$clientCustomerId' AND order_status='Waiting for Bids'";
        $result_get_orders = $this->dataBase->query($query_get_orders);

        if ($result_get_orders) {

            $orders = array();

            while ($row_get_orders = $this->dataBase->fetchArray($result_get_orders)) {
                array_push($orders, array(
                    "orderId" => $row_get_orders['orderId'],
                    "cod" => $row_get_orders['cod'],
                    "dateCreated" => $row_get_orders['dateCreated'],
                    "customerName" => $row_get_orders['customerName'],
                    "customerPhone" => $row_get_orders['customerPhone'],
                    "otherDetails" => $row_get_orders['otherDetails'],
                    "customerArea" => $row_get_orders['customerArea']
                ));
            }

            return array("response" => $orders);
        } else {
            echo " - (getAllNewOrders) get orders query error - ";
        }
    }

    // active orders
    public function getAllActiveOrders($clientCustomerId, $langId)
    {
        $query_get_orders = "SELECT 
                                engin.id AS orderId,
                                engin.createdAt AS dateCreated,
                                reciverAddress.name AS customerName,
                                reciverAddress.phone_number AS customerPhone,
                                reciverAddress.details AS otherDetails,
                                receiverArea.name AS customerArea,
                                transporter.AccountName AS transporterName
                                FROM togo.orderbidengin AS engin
                                INNER JOIN togo.orderbidaddress AS addresses ON engin.id = addresses.IdOrderBidEngin
                                LEFT OUTER JOIN togo.addresses AS reciverAddress ON addresses.ReciverAddressId = reciverAddress.id
                                LEFT OUTER JOIN togo.arealang AS receiverArea ON reciverAddress.areaId = receiverArea.areaId AND receiverArea.languageId = '$langId'
                                LEFT OUTER JOIN togo.transportertable AS transporter ON engin.DeliveryId = transporter.CustomerId
                                WHERE engin.TypeLoad = 1 AND engin.CustomerId = '$clientCustomerId' AND (engin.order_status='Bid Accepted' OR engin.order_status='Out for Delivery')";
        $result_get_orders = $this->dataBase->query($query_get_orders);

        if ($result_get_orders) {

            $orders = array();

            while ($row_get_orders = $this->dataBase->fetchArray($result_get_orders)) {
                array_push($orders, array(
                    "orderId" => $row_get_orders['orderId'],
                    "dateCreated" => $row_get_orders['dateCreated'],
                    "customerName" => $row_get_orders['customerName'],
                    "customerPhone" => $row_get_orders['customerPhone'],
                    "otherDetails" => $row_get_orders['otherDetails'],
                    "customerArea" => $row_get_orders['customerArea'],
                    "transporterName" => $row_get_orders['transporterName']
                ));
            }

            return array("response" => $orders);
        } else {
            echo " - (grtAllActiveOrders) get orders query error - ";
        }
    }

    // finished orders
    public function getAllFinishedOrders($clientCustomerId, $langId)
    {
        $query_get_orders = "SELECT 
                                engin.id AS orderId,
                                engin.createdAt AS dateCreated,
                                reciverAddress.name AS customerName,
                                reciverAddress.phone_number AS customerPhone,
                                reciverAddress.details AS otherDetails,
                                receiverArea.name AS customerArea,
                                transporter.AccountName AS transporterName
                                FROM togo.orderbidengin AS engin
                                INNER JOIN togo.orderbidaddress AS addresses ON engin.id = addresses.IdOrderBidEngin
                                LEFT OUTER JOIN togo.addresses AS reciverAddress ON addresses.ReciverAddressId = reciverAddress.id
                                LEFT OUTER JOIN togo.arealang AS receiverArea ON reciverAddress.areaId = receiverArea.areaId AND receiverArea.languageId = '$langId'
                                LEFT OUTER JOIN togo.transportertable AS transporter ON engin.DeliveryId = transporter.CustomerId
                                WHERE engin.TypeLoad = 1 AND engin.CustomerId = '$clientCustomerId' AND engin.order_status='Delivered'";
        $result_get_orders = $this->dataBase->query($query_get_orders);

        if ($result_get_orders) {

            $orders = array();

            while ($row_get_orders = $this->dataBase->fetchArray($result_get_orders)) {
                array_push($orders, array(
                    "orderId" => $row_get_orders['orderId'],
                    "dateCreated" => $row_get_orders['dateCreated'],
                    "customerName" => $row_get_orders['customerName'],
                    "customerPhone" => $row_get_orders['customerPhone'],
                    "otherDetails" => $row_get_orders['otherDetails'],
                    "customerArea" => $row_get_orders['customerArea'],
                    "transporterName" => $row_get_orders['transporterName']
                ));
            }

            return array("response" => $orders);
        } else {
            echo " - (getAllFinishedOrders) get orders query error - ";
        }
    }

    // order details
    public function getClientOrderDetails($orderId, $langId)
    {
        $query_get_order_details = "SELECT 
                                engin.id AS orderId,
                                engin.createdAt AS dateCreated,
                                reciverAddress.name AS customerName,
                                reciverAddress.phone_number AS customerPhone,
                                reciverAddress.details AS otherDetails,
                                reciverAddress.deliveryCost,
                                receiverArea.name AS customerArea,
                                transporter.AccountName AS transporterName
                                FROM togo.orderbidengin AS engin
                                INNER JOIN togo.orderbidaddress AS addresses ON engin.id = addresses.IdOrderBidEngin
                                LEFT OUTER JOIN togo.addresses AS reciverAddress ON addresses.ReciverAddressId = reciverAddress.id
                                LEFT OUTER JOIN togo.arealang AS receiverArea ON reciverAddress.areaId = receiverArea.areaId AND receiverArea.languageId = '$langId'
                                LEFT OUTER JOIN togo.transportertable AS transporter ON engin.DeliveryId = transporter.CustomerId
                                WHERE engin.id = '$orderId'";
        $result_get_order_details = $this->dataBase->query($query_get_order_details);

        if ($result_get_order_details) {

            $num_rows_get_order_details = $this->dataBase->numRows($result_get_order_details);

            if ($num_rows_get_order_details == 0) {
                echo " - (getClientOrderDetails) order [" . $orderId . "] not found error - ";
                return;
            }

            $order_details = array();

            $row_get_order_details = $this->dataBase->fetchArray($result_get_order_details);

            array_push($order_details, array(
                "orderId" => $row_get_order_details['orderId'],
                "dateCreated" => $row_get_order_details['dateCreated'],
                "customerName" => $row_get_order_details['customerName'],
                "customerPhone" => $row_get_order_details['customerPhone'],
                "otherDetails" => $row_get_order_details['otherDetails'],
                "deliveryCost" => $row_get_order_details['deliveryCost'],
                "customerArea" => $row_get_order_details['customerArea'],
                "transporterName" => $row_get_order_details['transporterName']
            ));

            return array("response" => $order_details);
        } else {
            echo " - (getClientOrderDetails) get orders query error - ";
        }
    }

    // order details / cancel new order
    public function clientCancelNewOrder($orderId)
    {
        // get order status
        $query_get_order_status = "SELECT order_status FROM togo.orderbidengin WHERE id = '$orderId'";
        $result_get_order_status = $this->dataBase->query($query_get_order_status);

        if (!$result_get_order_status) {
            echo " - (clientCancelNewOrder) get_order_status query error - ";
            return;
        }

        $row_count_get_order_status = $this->dataBase->numRows($result_get_order_status);

        if ($row_count_get_order_status == 0) {
            echo " - (clientCancelNewOrder) order [" . $orderId . "] not found error - ";
            return;
        }

        $row_get_order_status = $this->dataBase->fetchArray($result_get_order_status);
        $order_status = $row_get_order_status['order_status'];

        if ($order_status == "Deleted") {
            echo " - (clientCancelNewOrder) order [" . $orderId . "] already deleted error - ";
            return;
        }

        if ($order_status == "Delivered") {
            echo " - (clientCancelNewOrder) order [" . $orderId . "] delivered error - ";
            return;
        }

        if ($order_status == "Out for Delivery" || $order_status == "Bid Accepted") {
            echo " - (clientCancelNewOrder) order [" . $orderId . "] is active error - ";
            return;
        }

        if ($order_status == "Waiting for Bids") {

            // cancel the order

            $query_cancel_order = "UPDATE togo.orderbidengin SET IsDeleted = 1, order_status = 'Deleted' WHERE id = '$orderId'";
            $result_cancel_order = $this->dataBase->query($query_cancel_order);

            if ($result_cancel_order) {

                echo "order " . $orderId . " canceled successfully";
            } else {
                echo " - (clientCancelNewOrder) cancel_order query error - ";
            }
        } else {
            echo " - (clientCancelNewOrder) unknown order status [" . $order_status . "] error - ";
        }
    }

    // map
    public function getCustomersLocations($clientCustomerId)
    {
        $query_get_customers = "SELECT name, phone_number, latitude, longitude FROM togo.addresses WHERE customer_id='$clientCustomerId'";
        $result_get_customers = $this->dataBase->query($query_get_customers);

        if ($result_get_customers) {

            $customers_locations = array();

            while ($row_get_customers = $this->dataBase->fetchArray($result_get_customers)) {
                array_push($customers_locations, array("name" => $row_get_customers['name'], "phone" => $row_get_customers['phone_number'], "latitude" => $row_get_customers['latitude'], "longitude" => $row_get_customers['longitude']));
            }

            return $customers_locations;
        } else {
            echo " - (getCustomersLocations) get customers query error - ";
        }
    }

    // map
    // transportertable -> add isFoodTransporter, long, lat
    public function getTransportersLocations(/* client area */)
    {
        // SELECT long, lat FROM togo.transportertable WHERE... (near the client location)
        echo "getTransportersLocations";
    }

    // new order/view customers customers
    public function getCustomers($clientCustomerId, $langId)
    {

        $query_get_customers = "SELECT id, phone_number, name, details, areaId FROM togo.addresses WHERE customer_id='$clientCustomerId' AND is_default != 1";
        $result_get_customers = $this->dataBase->query($query_get_customers);

        if ($result_get_customers) {

            $customers = array();

            while ($row_get_customers = $this->dataBase->fetchArray($result_get_customers)) {

                // get area name
                $temp_area_id = $row_get_customers['areaId'];
                $query_get_area_name = "SELECT name FROM togo.arealang WHERE areaId='$temp_area_id' AND languageId='$langId'";
                $result_get_area_name = $this->dataBase->query($query_get_area_name);

                if ($result_get_area_name) {

                    $row_get_area_name = $this->dataBase->fetchArray($result_get_area_name);

                    $temp_area_name = $row_get_area_name['name'];

                    array_push($customers, array("id" => $row_get_customers['id'], "phone" => $row_get_customers['phone_number'], "name" => $row_get_customers['name'], "area" => $temp_area_name, "details" => $row_get_customers['details']));
                } else {

                    return array("status" => "error", "error" => "(getCustomers) get area (" . $temp_area_id . ") name query error");
                }
            }

            return array("status" => "success", "customers" => $customers);
        } else {
            return array("status" => "error", "error" => "(getCustomers) get customers query error");
        }
    }

    // new order customers
    public function getClientLocations()
    {
        // same as getCustomerInfo function above
        echo "getClientLocations";
    }

    // create order
    public function createFoodOrder($deliveryParams, $addresses, $customerId)
    {

        $deliveryParams = json_decode($deliveryParams, true);
        $addresses = json_decode($addresses, true);

        $prepTime = $deliveryParams['prepTime'];
        $CostLoad = $deliveryParams['CostLoad'];
        $DateLoad = "CURRENT_TIMESTAMP";

        $customerAddressId = $addresses['ReciverAddressId'];

        // get customer address
        $query_get_customer_address = "SELECT * FROM togo.addresses WHERE id='$customerAddressId'";
        $result_get_customer_address = $this->dataBase->query($query_get_customer_address);

        if ($result_get_customer_address) {

            $num_rows_get_customer_address = $this->dataBase->numRows($result_get_customer_address);

            if ($num_rows_get_customer_address > 0) {

                $row_get_customer_address = $this->dataBase->fetchArray($result_get_customer_address);
                $deliveryCost = $row_get_customer_address['deliveryCost'];

                // insert into orderbidengin
                // (add prepTime to orderbidengin)
                $query_insert_order = "INSERT INTO togo.orderbidengin (CustomerId, deliveryWay, DateLoad, CostLoad, prepTime, TypeLoad, order_status) 
                                        VALUES ('$customerId', '2', $DateLoad, '$CostLoad', '$prepTime', '1', 'Waiting for Bids')";
                $result_insert_order = $this->dataBase->query($query_insert_order);

                if ($result_insert_order) {

                    // get last inserted order id
                    // $query_get_last_order_id = "SELECT id FROM togo.OrderBidEngin WHERE createdAt=(SELECT MAX(createdAt) FROM togo.OrderBidEngin)";
                    $query_get_last_order_id = "SELECT id FROM togo.OrderBidEngin WHERE CustomerId='$customerId' ORDER BY id DESC LIMIT 1";
                    $result_get_last_order_id = $this->dataBase->query($query_get_last_order_id);

                    if ($result_get_last_order_id) {

                        $num_rows_get_last_order_id = $this->dataBase->numRows($result_get_last_order_id);

                        if ($num_rows_get_last_order_id > 0) {

                            $row_get_last_order_id = $this->dataBase->fetchArray($result_get_last_order_id);
                            $last_inserted_order = $row_get_last_order_id['id'];

                            // insert into orderbidaddress ????

                            // sender info
                            // (sender address info will be restaurant's default address)
                            // get sender address info by $customerId

                            $query_get_sender_info = "SELECT 
                            id AS SenderAddressId,
                            details AS OtherDetails,
                            areaId AS IdArea,
                            cityId AS IdCity,
                            latitude AS LatSender,
                            longitude AS LongSender
                            FROM togo.addresses WHERE customer_id = '$customerId' AND is_default = 1";
                            $result_get_sender_info = $this->dataBase->query($query_get_sender_info);

                            if (!$result_get_sender_info) {
                                echo " - (createFoodOrder) get_sender_info query error - ";
                                return;
                            }

                            $row_get_sender_info = $this->dataBase->fetchArray($result_get_sender_info);

                            $SenderAddressId = $row_get_sender_info['SenderAddressId'];
                            $sourceAreaId = $row_get_sender_info['IdArea'];
                            $sourceCityId = $row_get_sender_info['IdCity'];
                            $senderAddressOtherDetails = $row_get_sender_info['OtherDetails'];
                            $LatSender = $row_get_sender_info['LatSender'];
                            $LongSender = $row_get_sender_info['LongSender'];

                            // receiver info
                            $ReciverAddressId = $customerAddressId;
                            $taretAreaId = $row_get_customer_address['areaId'];
                            $targetCityId = $row_get_customer_address['cityId'];
                            $receiverAddressOtherDetails = $row_get_customer_address['details'];
                            $ReceiverAddressNum = $row_get_customer_address['phone_number'];
                            $LatReciver = $row_get_customer_address['latitude'];
                            $LongReciver = $row_get_customer_address['longitude'];

                            if (empty($LatSender))
                                $LatSender = "0";
                            if (empty($LongSender))
                                $LongSender = "0";
                            if (empty($LatReciver))
                                $LatReciver = "0";
                            if (empty($LongReciver))
                                $LongReciver = "0";

                            $query_insert_order_address = "INSERT INTO togo.orderbidaddress (IdArea, IdCity, OtherDetails, LatSender, LongSender, IdAreaDes, IdCityDes, OtherDetailsDes, LatReciver, LongReciver, IdOrderBidEngin, ReceiverAddressNum, SenderAddressId, ReciverAddressId)
                                                            VALUES ('$sourceAreaId', '$sourceCityId', '$senderAddressOtherDetails', '$LatSender', '$LongSender', '$taretAreaId', '$targetCityId', '$receiverAddressOtherDetails', '$LatReciver', '$LongReciver', '$last_inserted_order', '$ReceiverAddressNum', '$SenderAddressId', '$ReciverAddressId')";
                            $result_insert_order_address = $this->dataBase->query($query_insert_order_address);

                            if ($result_insert_order_address) {

                                if (false) { // disabled
                                    // insert into addresses ???? ((((((( ********already inserted******** )))))))
                                    // (attach the COD to the address(customer))

                                    /* note: foodCustomers table won't be needed because addresses table can be used as a customer (in order to fit food orders logic with the old order logic) */

                                    // insert actions, pick a transporter, send notifications, SMSs, and what not...

                                    // pick a transporter logic

                                    // get restaurant coordinates
                                    $query_get_restaurant_info = "SELECT address.latitude, address.longitude 
                                    FROM togo.addresses AS address 
                                    WHERE address.customer_id = '$customerId' AND address.is_default = 1";

                                    $result_get_restaurant_info = $this->dataBase->query($query_get_restaurant_info);

                                    if (!$result_get_restaurant_info) {
                                        echo " - (createFoodOrder) get_restaurant_info query error - ";
                                        return;
                                    }

                                    $row_count_get_restaurant_info = $this->dataBase->numRows($result_get_restaurant_info);

                                    if ($row_count_get_restaurant_info == 0) {
                                        echo " - (createFoodOrder) restaurant address not found error - ";
                                        return;
                                    }

                                    $row_get_restaurant_info = $this->dataBase->fetchArray($result_get_restaurant_info);

                                    $restaurant_long = floatval($row_get_restaurant_info['longitude']);
                                    $restaurant_lat = floatval($row_get_restaurant_info['latitude']);

                                    // get available transporters' infos & coordinates
                                    // (available: isActive -> not offline , isAvailable -> not with active delivery)

                                    $query_get_transporters_infos = "SELECT cust.id, trans.longitude, trans.latitude, trans.updatedAt
                                    FROM togo.customer AS cust
                                    INNER JOIN togo.transportertable AS trans ON cust.id = trans.CustomerId
                                    WHERE cust.IsTransporter = 1 AND trans.isFoodTransporter = 1 AND trans.isActive = 1 AND trans.isAvailable = 1";

                                    $result_get_transporters_infos = $this->dataBase->query($query_get_transporters_infos);

                                    if (!$result_get_transporters_infos) {
                                        echo " - (createFoodOrder) get_transporters_infos query error - ";
                                        return;
                                    }

                                    // loop over transporters to get their coordinates

                                    $transporters_coordinates = array();
                                    while ($row_get_transporters_infos = $this->dataBase->fetchArray($result_get_transporters_infos)) {

                                        // last time location updated
                                        $updated_at = $row_get_transporters_infos['updatedAt'];
                                        // check if location is updated so the transporter is not offline
                                        $current_time = date("Y-m-d H:i:s");

                                        $timestamp1 = strtotime($current_time);
                                        $timestamp2 = strtotime($updated_at);

                                        $difference = abs($timestamp2 - $timestamp1); // Absolute difference in seconds

                                        if ($difference > 20) {
                                            // Skip the loop iteration
                                            continue;
                                        }

                                        $long = floatval($row_get_transporters_infos['longitude']);
                                        $lat = floatval($row_get_transporters_infos['latitude']);

                                        array_push($coordinates, array(
                                            "transId" => $row_get_transporters_infos['transId'],
                                            "latitude" => $lat,
                                            "longitude" => $long
                                        ));
                                    }
                                }

                                // ##############################################
                                // THE LOGIC...

                                // get restaurant location (long, lat)
                                // get transporters coordinates (longs, lats) & ordres history
                                // declare radius limit

                                if (true) { // inabled

                                    // get restaurant location
                                    $query_get_restaurant_location = "SELECT longitude, latitude FROM togo.addresses WHERE customer_id = '$customerId' AND is_default = 1";
                                    $result_get_restaurant_location = $this->dataBase->query($query_get_restaurant_location);
                                    if (!$result_get_restaurant_location) {
                                        echo " - (createFoodOrder) get_restaurant_location query error - ";
                                        return;
                                    }

                                    $row_count_get_restaurant_location = $this->dataBase->numRows($result_get_restaurant_location);

                                    if ($row_count_get_restaurant_location == 0) {
                                        echo " - (createFoodOrder) restaurant coordinates not found error - ";
                                        return;
                                    }

                                    $row_get_restaurant_location = $this->dataBase->fetchArray($result_get_restaurant_location);

                                    $restaurantLocation = array(
                                        "longitude" => $row_get_restaurant_location['longitude'],
                                        "latitude" => $row_get_restaurant_location['latitude']
                                    );

                                    // get transporters locations (and orderes history?!)
                                    $query_get_transporters_infos = "SELECT
                                    t.CustomerId AS id,
                                    c.Token AS token,
                                    t.longitude,
                                    t.latitude,
                                    t.updatedAt
                                    FROM togo.transportertable AS t
                                    INNER JOIN togo.customer AS c ON t.CustomerId = c.id
                                    WHERE t.isFoodTransporter = 1 AND c.loggedIn = 1 AND t.isActive = 1";

                                    $result_get_transporters_infos = $this->dataBase->query($query_get_transporters_infos);

                                    if (!$result_get_transporters_infos) {
                                        echo " - (createFoodOrder) get_transporter_infos query error - ";
                                        return;
                                    }

                                    $row_count_get_transporter_infos = $this->dataBase->numRows($result_get_transporters_infos);

                                    if ($row_count_get_transporter_infos == 0) {
                                        echo " - (createFoodOrder) transporters not found error - ";
                                        return;
                                    }

                                    $transporters = array();

                                    file_put_contents("test_dates.log", " \n ************************************ \n", FILE_APPEND);

                                    while ($row_get_transporters_infos = $this->dataBase->fetchArray($result_get_transporters_infos)) {

                                        // last time location updated
                                        $updated_at = $row_get_transporters_infos['updatedAt'];
                                        // check if location is updated so the transporter is not offline
                                        // date_default_timezone_set('Asia/Jerusalem');
                                        $current_time = date("Y-m-d H:i:s");

                                        $timestamp1 = strtotime($current_time);
                                        $timestamp2 = strtotime($updated_at);

                                        $difference = abs($timestamp2 - $timestamp1); // Absolute difference in seconds

                                        file_put_contents("test_dates.log", var_export("updated_at: " . $updated_at . ", current_time: " . $current_time, true) . "\n ================ \n", FILE_APPEND);
                                        file_put_contents("test_dates.log", var_export("difference: " . $difference, true) . "\n ================ \n", FILE_APPEND);

                                        if ($difference > 20) {
                                            // Skip the loop iteration
                                            continue;
                                        }

                                        $long = floatval($row_get_transporters_infos['longitude']);
                                        $lat = floatval($row_get_transporters_infos['latitude']);

                                        array_push($transporters, array("id" => $row_get_transporters_infos['id'], "token" => $row_get_transporters_infos['token'], "longitude" => $row_get_transporters_infos['longitude'], "latitude" => $row_get_transporters_infos['latitude']));
                                    }

                                    $radiusLimit = 10.0; // 10km radius limit

                                    $sortedTransporters = $this->prioritizeTransporters($restaurantLocation, $transporters, $radiusLimit);

                                    // insert the list and notify the first one
                                    $priority_index = 1;
                                    foreach ($sortedTransporters as $transporter) {
                                        $transporter_id = $transporter['id'];

                                        $is_canceled = 0;
                                        if ($priority_index == 1) {
                                            $is_canceled = 1;
                                            $first_transoprter_id = $transporter['id'];
                                            $first_transoprter_token = $transporter['token'];
                                        }

                                        $query_insert_candidates = "INSERT INTO togo.deliverycandidates (isCanceled, orderId, transporterId, offerPriority)
                                        VALUES ($is_canceled, '$last_inserted_order', '$transporter_id', '$priority_index')";

                                        $result_insert_candidates = $this->dataBase->query($query_insert_candidates);

                                        if (!$result_insert_candidates) {
                                            echo " - (createFoodOrder) insert_candidates query error - ";
                                            return;
                                        }

                                        $priority_index++;
                                    }

                                    // notify first one

                                    // get restaurant name
                                    $query_get_restaurant_name_and_area = "SELECT
                                    clientbusinesstable.BusinessName AS restaurantName,
                                    area.name AS areaName
                                    FROM togo.clientbusinesstable
                                    LEFT OUTER JOIN togo.addresses ON clientbusinesstable.CustomerId = addresses.customer_id AND addresses.is_default = 1
                                    LEFT OUTER JOIN togo.arealang AS area ON addresses.areaId = area.areaId AND area.languageId = 2
                                    WHERE CustomerId='$customerId'";
                                    $result_get_restaurant_name_and_area = $this->dataBase->query($query_get_restaurant_name_and_area);

                                    if (!$result_get_restaurant_name_and_area) {
                                        echo " - (createFoodOrder) get_restaurant_name query error - ";
                                        return;
                                    }

                                    $row_get_restaurant_name_and_area = $this->dataBase->fetchArray($result_get_restaurant_name_and_area);
                                    $restaurant_name = $row_get_restaurant_name_and_area['restaurantName'];
                                    $restaurant_area = $row_get_restaurant_name_and_area['areaName'];

                                    // upload the audio
                                    $txt = "لديك طلبية جديدة من مطعمْ " . $restaurant_name . " إلى مَنْطِقَةْ " . $restaurant_area;
                                    $txt = htmlspecialchars($txt);
                                    $txt = rawurlencode($txt);
                                    $audio = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=ar-IN');

                                    // Specify the output file path
                                    $outputFilePath = "../audio/transporterFoodAudio/output_audio_" . $last_inserted_order . ".mp3";  // Change this to your desired file name and format

                                    // Save the audio to the output file
                                    file_put_contents($outputFilePath, $audio);

                                    $data = array(
                                        'orderId' => $last_inserted_order,
                                        'clientName' => $restaurant_name,
                                        'msg' => "لديك طلبية جديدة من مطعمْ " . $restaurant_name . " إلى مَنْطِقَةْ " . $restaurant_area,
                                        'audioURL' => "audio/transporterFoodAudio/output_audio_" . $last_inserted_order . ".mp3",
                                        'clientImageUrl' => "",
                                        'intent' => 'orderCreated'
                                    );

                                    $tokens = array();

                                    array_push($tokens, $first_transoprter_token);

                                    $this->sendFCMNotification(
                                        $tokens,
                                        null,
                                        $data,
                                        null,
                                        null
                                    );
                                }

                                // echo " - food order [" . $last_inserted_order . "] created successfully - ";
                                echo json_encode(array("response" => "food order [" . $last_inserted_order . "] created successfully", "orderId" => $last_inserted_order));

                                $title = "Order Created";
                                $message = "Order " . $last_inserted_order . " Created";
                                $this->recordAction($last_inserted_order, $title, $message);
                            } else {
                                echo " - (createFoodOrder) insert_order_address query error - ";
                            }
                        } else {
                            echo " - (createFoodOrder) last inserted order id not found error - ";
                        }
                    } else {
                        echo " - (createFoodOrder) get_last_order_id query error - ";
                    }
                } else {
                    echo " - (createFoodOrder) create-order query error - ";
                }
            } else {
                echo " - (createFoodOrder) receiver address not found error - ";
            }
        } else {
            echo " - (createFoodOrder) get_customer_address query error - ";
        }
    }

    public function testFunc()
    {

        // upload the audio
        $txt = "لديك طلبية جديدة من مطعمْ " . "moh res" . " إلى مَنْطِقَةْ " . "batata";
        $txt = htmlspecialchars($txt);
        $txt = rawurlencode($txt);
        $audio = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=ar-IN');

        // Specify the output file path
        $outputFilePath = "../audio/transporterFoodAudio/output_audio_2.mp3";  // Change this to your desired file name and format

        // Save the audio to the output file
        $res = file_put_contents($outputFilePath, $audio);

        echo json_encode($res);

        return;

        // get restaurant location
        $query_get_restaurant_location = "SELECT longitude, latitude FROM togo.addresses WHERE customer_id = 266 AND is_default = 1";
        $result_get_restaurant_location = $this->dataBase->query($query_get_restaurant_location);
        if (!$result_get_restaurant_location) {
            echo " - (createFoodOrder) get_restaurant_location query error - ";
            return;
        }

        $row_count_get_restaurant_location = $this->dataBase->numRows($result_get_restaurant_location);

        if ($row_count_get_restaurant_location == 0) {
            echo " - (createFoodOrder) restaurant coordinates not found error - ";
            return;
        }

        $row_get_restaurant_location = $this->dataBase->fetchArray($result_get_restaurant_location);

        $restaurantLocation = array(
            "longitude" => $row_get_restaurant_location['longitude'],
            "latitude" => $row_get_restaurant_location['latitude']
        );

        // get transporters locations (and orderse history?!)
        $query_get_transporters_infos = "SELECT
        t.CustomerId AS id,
        c.Token AS token,
        t.longitude,
        t.latitude
        FROM togo.transportertable AS t
        INNER JOIN togo.customer AS c ON t.CustomerId = c.id
        WHERE isFoodTransporter = 1";

        $result_get_transporters_infos = $this->dataBase->query($query_get_transporters_infos);

        if (!$result_get_transporters_infos) {
            echo " - (createFoodOrder) get_transporter_infos query error - ";
            return;
        }

        $row_count_get_transporter_infos = $this->dataBase->numRows($result_get_transporters_infos);

        if ($row_count_get_transporter_infos == 0) {
            echo " - (createFoodOrder) transporters not found error - ";
            return;
        }

        $transporters = array();

        while ($row_get_transporters_infos = $this->dataBase->fetchArray($result_get_transporters_infos)) {
            array_push($transporters, array("id" => $row_get_transporters_infos['id'], "token" => $row_get_transporters_infos['token'], "longitude" => $row_get_transporters_infos['longitude'], "latitude" => $row_get_transporters_infos['latitude']));
        }

        $radiusLimit = 10.0; // 10km radius limit

        $sortedTransporters = $this->prioritizeTransporters($restaurantLocation, $transporters, $radiusLimit);

        // insert the list and notify the first one
        $priority_index = 1;
        foreach ($sortedTransporters as $transporter) {
            $transporter_id = $transporter['id'];

            $is_canceled = 0;
            if ($priority_index == 1) {
                $is_canceled = 1;
                $first_transoprter_id = $transporter['id'];
                $first_transoprter_token = $transporter['token'];
            }

            $query_insert_candidates = "INSERT INTO togo.deliverycandidates (isCanceled, orderId, transporterId, offerPriority)
            VALUES ('$is_canceled', '1234', '$transporter_id', '$priority_index')";

            $result_insert_candidates = $this->dataBase->query($query_insert_candidates);

            $priority_index++;
        }

        // notify first one

        // get restaurant name
        $query_get_restaurant_name_and_area = "SELECT
        clientbusinesstable.BusinessName AS restaurantName,
        addresses.name AS areaName
        FROM togo.clientbusinesstable
        LEFT OUTER JOIN togo.addresses ON clientbusinesstable.CustomerId = addresses.customer_id AND addresses.is_default = 1
        WHERE CustomerId=266";
        $result_get_restaurant_name_and_area = $this->dataBase->query($query_get_restaurant_name_and_area);

        if (!$result_get_restaurant_name_and_area) {
            echo " - (createFoodOrder) get_restaurant_name query error - ";
            return;
        }

        $row_get_restaurant_name_and_area = $this->dataBase->fetchArray($result_get_restaurant_name_and_area);
        $restaurant_name = $row_get_restaurant_name_and_area['restaurantName'];
        $restaurant_area = $row_get_restaurant_name_and_area['areaName'];

        // update the audio

        $data = array(
            'orderId' => '1234',
            'clientName' => $restaurant_name,
            'msg' => "لديك طلبية جديدة من مطعمْ " . $restaurant_name . " إلى مَنْطِقَةْ " . $restaurant_area,
            'audioURL' => 'img/BusinessLogo/output_audio.mp3',
            'clientImageUrl' => "",
            'intent' => 'orderCreated'
        );

        $tokens = array();

        array_push($tokens, $first_transoprter_token);

        /* $this->sendFCMNotification(
            $tokens,
            null,
            $data,
            null,
            null
        ); */

        echo json_encode($tokens);
        echo json_encode($data);

        return;

        // Example usage
        $restaurantLocation = [12.34, 56.78]; // Example restaurant location [longitude, latitude]
        $transporters = [
            ['location' => [12.35, 56.77], 'ordersDelivered' => 10], // Transporter 1
            ['location' => [12.36, 56.79], 'ordersDelivered' => 8],  // Transporter 2
            ['location' => [12.33, 56.81], 'ordersDelivered' => 5],  // Transporter 3
            // ... more transporters ...
        ];

        $radiusLimit = 3.0; // 3km radius limit

        $sortedTransporters = $this->prioritizeTransporters($restaurantLocation, $transporters, $radiusLimit);

        // Now $sortedTransporters contains the sorted list of transporters based on priority
        echo json_encode($sortedTransporters);
    }

    private function prioritizeTransporters($restaurantLocation, $transporters, $radiusLimit)
    {
        $filteredTransporters = [];

        // Filter transporters within the radius limit
        foreach ($transporters as $transporter) {
            $distance = $this->calculateDistance($restaurantLocation, $transporter['location']);
            if ($distance <= $radiusLimit) {
                $filteredTransporters[] = $transporter;
            }
        }

        // Sort transporters by priority (nearest first, lower orders delivered first)
        usort($filteredTransporters, function ($a, $b) use ($restaurantLocation) {
            $distanceA = $this->calculateDistance($restaurantLocation, $a['location']);
            $distanceB = $this->calculateDistance($restaurantLocation, $b['location']);

            if ($distanceA != $distanceB) {
                return $distanceA - $distanceB;
            } else {
                return $a['ordersDelivered'] - $b['ordersDelivered'];
            }
        });

        return $filteredTransporters;
    }

    private function calculateDistance($location1, $location2)
    {
        list($lon1, $lat1) = $location1;
        list($lon2, $lat2) = $location2;

        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }

    // ------------------------- (transporter functions) -------------------------

    // main
    // personal info including wallet
    public function getTransporterPersonalInfo($transporterCustomerId)
    {
        // select from customer, transportertable
        echo "getTransporterPersonalInfo";
    }

    public function updateAvailability($transporterCustomerId, $status)
    {
        $query_update_status = "UPDATE togo.transportertable SET isActive = $status WHERE CustomerId = '$transporterCustomerId'";
        $result_update_status = $this->dataBase->query($query_update_status);

        if ($result_update_status) {
            echo "status updated successfully";
        } else {
            echo " - (updateAvailability) update_status query error - ";
        }
    }

    public function updateTransporterLocation($transporterCustomerId, $long, $lat)
    {
        $query_update_location = "UPDATE togo.transportertable SET longitude = '$long', latitude = '$lat', isActive = 1 WHERE CustomerId = '$transporterCustomerId'";
        $result_update_location = $this->dataBase->query($query_update_location);

        if ($result_update_location) {
            echo "location updated successfully";
        } else {
            echo " - (updateTransporterLocation) update_location query error - ";
        }
    }

    // new orders
    public function getTransporterNewOrders($transporterCustomerId)
    {
        // SELECT * FROM togo.orderbidengin WHERE TypeLoad = 1 AND DeliveryId = '$transporterCustomerId' AND order_status='Waiting for Bids'
        echo "getTransporterNewOrders";
    }

    // active orders
    public function getTransporterActiveOrders($transporterCustomerId, $lang)
    {
        $query_get_orders = "SELECT 
        engin.id AS orderId,
        engin.order_status AS orderStatue,
        engin.CostLoad AS cod,
        engin.DateLoad AS dateCreated,
        engin.prepTime AS prepTime,
        senderArea.name AS senderArea,
        senderAddress.name AS senderName,
        senderAddress.phone_number AS senderPhone,
        senderAddress.details AS senderOtherDetails,
        receiverArea.name AS receiverArea,
        receiverAddress.name AS receiverName,
        receiverAddress.phone_number AS receiverPhone,
        receiverAddress.details AS receiverOtherDetails
        FROM togo.orderbidengin AS engin
        INNER JOIN togo.orderbidaddress AS addresses ON engin.id = addresses.IdOrderBidEngin
        LEFT OUTER JOIN togo.addresses AS senderAddress ON addresses.SenderAddressId = senderAddress.id
        LEFT OUTER JOIN togo.addresses AS receiverAddress ON addresses.ReciverAddressId = receiverAddress.id
        LEFT OUTER JOIN togo.arealang AS senderArea ON senderAddress.areaId = senderArea.areaId AND senderArea.languageId = '$lang'
        LEFT OUTER JOIN togo.arealang AS receiverArea ON receiverAddress.areaId = receiverArea.areaId AND receiverArea.languageId = '$lang'
        WHERE engin.DeliveryId = '$transporterCustomerId' AND (engin.order_status = 'Bid Accepted' OR engin.order_status = 'Out for Delivery')";

        $result_get_orders = $this->dataBase->query($query_get_orders);

        if (!$result_get_orders) {
            echo " - (getTransporterActiveOrders) get_orders query error - ";
            return;
        }

        $orders = array();

        while ($row_get_orders = $this->dataBase->fetchArray($result_get_orders)) {
            array_push($orders, array(
                "orderId" => $row_get_orders['orderId'],
                "orderStatue" => $row_get_orders['orderStatue'],
                "cod" => $row_get_orders['cod'],
                "prepTime" => $row_get_orders['prepTime'],
                "dateCreated" => $row_get_orders['dateCreated'],
                "senderArea" => $row_get_orders['senderArea'],
                "senderName" => $row_get_orders['senderName'],
                "senderPhone" => $row_get_orders['senderPhone'],
                "senderOtherDetails" => $row_get_orders['senderOtherDetails'],
                "receiverArea" => $row_get_orders['receiverArea'],
                "receiverName" => $row_get_orders['receiverName'],
                "receiverPhone" => $row_get_orders['receiverPhone'],
                "receiverOtherDetails" => $row_get_orders['receiverOtherDetails']
            ));
        }

        return array("response" => $orders);
    }

    // finished orders
    public function getTransporterFinsihedOrders($transporterCustomerId, $lang)
    {
        $query_get_orders = "SELECT 
        engin.id AS orderId,
        engin.order_status AS orderStatue,
        engin.CostLoad AS cod,
        engin.DateLoad AS dateCreated,
        engin.prepTime AS prepTime,
        senderArea.name AS senderArea,
        senderAddress.name AS senderName,
        senderAddress.phone_number AS senderPhone,
        senderAddress.details AS senderOtherDetails,
        receiverArea.name AS receiverArea,
        receiverAddress.name AS receiverName,
        receiverAddress.phone_number AS receiverPhone,
        receiverAddress.details AS receiverOtherDetails
        FROM togo.orderbidengin AS engin
        INNER JOIN togo.orderbidaddress AS addresses ON engin.id = addresses.IdOrderBidEngin
        LEFT OUTER JOIN togo.addresses AS senderAddress ON addresses.SenderAddressId = senderAddress.id
        LEFT OUTER JOIN togo.addresses AS receiverAddress ON addresses.ReciverAddressId = receiverAddress.id
        LEFT OUTER JOIN togo.arealang AS senderArea ON senderAddress.areaId = senderArea.areaId AND senderArea.languageId = '$lang'
        LEFT OUTER JOIN togo.arealang AS receiverArea ON receiverAddress.areaId = receiverArea.areaId AND receiverArea.languageId = '$lang'
        WHERE engin.DeliveryId = '$transporterCustomerId' AND engin.order_status = 'Delivered'";

        $result_get_orders = $this->dataBase->query($query_get_orders);

        if (!$result_get_orders) {
            echo " - (getTransporterFinsihedOrders) get_orders query error - ";
            return;
        }

        $orders = array();

        while ($row_get_orders = $this->dataBase->fetchArray($result_get_orders)) {
            array_push($orders, array(
                "orderId" => $row_get_orders['orderId'],
                "orderStatue" => $row_get_orders['orderStatue'],
                "cod" => $row_get_orders['cod'],
                "prepTime" => $row_get_orders['prepTime'],
                "dateCreated" => $row_get_orders['dateCreated'],
                "senderArea" => $row_get_orders['senderArea'],
                "senderName" => $row_get_orders['senderName'],
                "senderPhone" => $row_get_orders['senderPhone'],
                "senderOtherDetails" => $row_get_orders['senderOtherDetails'],
                "receiverArea" => $row_get_orders['receiverArea'],
                "receiverName" => $row_get_orders['receiverName'],
                "receiverPhone" => $row_get_orders['receiverPhone'],
                "receiverOtherDetails" => $row_get_orders['receiverOtherDetails']
            ));
        }

        return array("response" => $orders);
    }

    public function getTransporterOrderDetails()
    {
        echo "getTransporterOrderDetails";
    }

    public function getTransporterTransactions($transporterCustomerId)
    {
        require_once('OdooService.php');
        $odooService = new OdooService_2();

        $params = json_encode(array(
            "jsonrpc" => "2.0",
            "params" => array("customer_id" => $transporterCustomerId)
        ));

        $data = $odooService->callOdooUrl("/partner/daily_entries", "POST", $params);

        return $data;
    }

    // new orders / order details
    public function responseToNewOrder($transporterCustomerId, $orderId, $response)
    {
        if ($response == "reject") {

            // pop the current transporter from the candidates-table
            // notify the next one in the list (the list is already sorted and inserted by THE logic)

            // cancel transporter
            $query_cancel_transporter = "UPDATE togo.deliverycandidates SET isCanceled = 1 WHERE orderId = '$orderId' AND transporterId = '$transporterCustomerId'";
            $result_cancel_transporter = $this->dataBase->query($query_cancel_transporter);

            if (!$result_cancel_transporter) {
                echo " - (responseToNewOrder) cancel_transporter query error - ";
                return;
            }

            // get next candidate
            $query_get_candidate = "SELECT
            deliverycandidates.transporterId,
            customer.Token AS token
            FROM togo.deliverycandidates
            LEFT OUTER JOIN togo.customer ON  deliverycandidates.transporterId = customer.id
            WHERE orderId = '$orderId' AND isCanceled = 0 AND isDeleted = 0 ORDER BY offerPriority LIMIT 1";
            $result_get_candidate = $this->dataBase->query($query_get_candidate);

            if (!$result_get_candidate) {
                echo " - (responseToNewOrder) get_candidat query error - ";
                return;
            }

            $row_count_get_candidate = $this->dataBase->numRows($result_get_candidate);

            if ($row_count_get_candidate == 0) {
                echo " - no transporters found - ";
                return;
            }

            $row_get_candidate = $this->dataBase->fetchArray($result_get_candidate);

            $transporter_id = $row_get_candidate['transporterId'];
            $transporter_token = $row_get_candidate['token'];

            // get restaurant name and area
            $query_get_rest_info = "SELECT
            restArea.name AS restAreaName,
            restClient.BusinessName AS restName
            FROM togo.orderbidengin AS engin
            LEFT OUTER JOIN togo.addresses AS restAdress ON engin.CustomerId = restAdress.customer_id AND restAdress.is_default = 1
            LEFT OUTER JOIN togo.arealang AS restArea ON restAdress.areaId = restArea.areaId AND restArea.languageId = 2
            LEFT OUTER JOIN togo.clientbusinesstable AS restClient ON engin.CustomerId = restClient.CustomerId
            WHERE engin.id = '$orderId'";

            $result_get_rest_info = $this->dataBase->query($query_get_rest_info);

            if (!$result_get_rest_info) {
                echo " - (responseToNewOrder) get_rest_info query error - ";
                return;
            }

            $row_get_rest_info = $this->dataBase->fetchArray($result_get_rest_info);

            $rest_name = $row_get_rest_info['restName'];
            $rest_area = $row_get_rest_info['restAreaName'];

            // send notification to the transporter
            $data = array(
                'orderId' => $orderId,
                'clientName' => $rest_name,
                'msg' => "لديك طلبية جديدة من مطعمْ " . $rest_name . " إلى مَنْطِقَةْ " . $rest_area,
                'audioURL' => "audio/transporterFoodAudio/output_audio_" . $orderId . ".mp3",
                'clientImageUrl' => "",
                'intent' => 'orderCreated'
            );

            $tokens = array();

            array_push($tokens, $transporter_token);

            $this->sendFCMNotification(
                $tokens,
                null,
                $data,
                null,
                null
            );

            echo " - offer rejected - ";
        } else if ($response == "accept") {

            // active order "Bid Accepted" with the current transporter id as the DeliveryId
            // clear the list on candidates

            // update order
            $query_update_order = "UPDATE togo.orderbidengin SET DeliveryId = '$transporterCustomerId', OriginalDeliveryId = '$transporterCustomerId', currentTransporterId = '$transporterCustomerId', order_status = 'Bid Accepted', IsAcceptDelivery = 1 WHERE id = '$orderId'";
            $result_update_order = $this->dataBase->query($query_update_order);

            if (!$result_update_order) {
                echo " - (responseToNewOrder) update_order query error - ";
            }

            // insert to tomeline and deliveryacceptordertable and do the transactions !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

            // get restaurant customer id, delivery price, and cod

            $query_get_customer_id_and_prices = "SELECT receiverAddress.deliveryCost, engin.CustomerId AS restaurantId, engin.CostLoad AS cod
            FROM togo.orderbidengin AS engin
            INNER JOIN togo.orderbidaddress as orderAddresses ON engin.id = orderAddresses.IdOrderBidEngin
            LEFT OUTER JOIN togo.addresses AS receiverAddress ON orderAddresses.ReciverAddressId = receiverAddress.id
            WHERE engin.id = '$orderId'";
            $result_get_customer_id_and_prices = $this->dataBase->query($query_get_customer_id_and_prices);

            if (!$result_get_customer_id_and_prices) {
                echo " - (responseToNewOrder) get_customer_id_and_delivery_price query error  - ";
                /* $data = "add_accepted_transporter query error";
                file_put_contents("create_order.log", var_export($data, true) . "\n ================ \n", FILE_APPEND); */
                return;
            }

            $row_get_customer_id_and_prices = $this->dataBase->fetchArray($result_get_customer_id_and_prices);
            $delivery_price = $row_get_customer_id_and_prices['deliveryCost'];
            $restaurant_id = $row_get_customer_id_and_prices['restaurantId'];
            $cod = $row_get_customer_id_and_prices['cod'];

            $assignDate = date("Y-m-d H:i:s");
            $query_add_accepted_transporter = "INSERT INTO togo.transporterstimelinetb 
            (order_id, transporter_id, assign_date, transporter_bidprice, isCurrent) VALUES
            ('$orderId', '$transporterCustomerId', '$assignDate', '$delivery_price', 1)";
            $result_add_accepted_transporter = $this->dataBase->query($query_add_accepted_transporter);

            if (!$result_add_accepted_transporter) {
                echo " - (responseToNewOrder) add_accepted_transporter query error  - ";
                return;
            }

            $query_insert_bid = "INSERT INTO togo.DeliveryAcceptOrderTable (IdOrder, IdTransporter, CostDelivery)
            VALUES ('$orderId', '$transporterCustomerId', '$delivery_price')";

            $result_insert_bid = $this->dataBase->query($query_insert_bid);

            if (!$result_insert_bid) {
                echo " - (responseToNewOrder) insert_bid query error  - ";
                return;
            }

            require_once('OdooService.php');
            $odooService = new OdooService();

            $var1 = $odooService->BalanceIsEnough($restaurant_id, $delivery_price);
            $var2 = $odooService->BalanceIsEnough($transporterCustomerId, $cod);
            if ($var1 == 1 && $var2 == 1) {
                $odooService->move_to_escrow($restaurant_id, $orderId, $delivery_price);
                $odooService->move_to_escrow($transporterCustomerId, $orderId, $cod);
            } else {
                echo " - (responseToNewOrder) balance not enough error, [var1: " . $var1 . ", var2: " . $var2 . "]  - ";

                // update order to wating for bids
                $query_undo_order = "UPDATE togo.orderbidengin SET DeliveryId = NULL, OriginalDeliveryId = NULL, currentTransporterId = NULL, order_status = 'Waiting for Bids', IsAcceptDelivery = NULL WHERE id = '$orderId'";
                $result_undo_order = $this->dataBase->query($query_undo_order);

                $query_delete_timeline_records = "DELETE FROM togo.transporterstimelinetb WHERE order_id = '$orderId'";
                $result_delete_timeline_records = $this->dataBase->query($query_delete_timeline_records);

                $query_delete_bids_records = "DELETE FROM togo.DeliveryAcceptOrderTable WHERE IdOrder = '$orderId'";
                $result_delete_bids_records = $this->dataBase->query($query_delete_bids_records);

                return;
            }
            /////////////////////////////////////////////////////////////////////////////////

            // set transporter as not available (isAvailable = 0 -> with active order)

            $query_set_unavailable = "UPDATE togo.transportertable SET isAvailable = 0 WHERE CustomerId = '$transporterCustomerId'";
            $result_set_unavailable = $this->dataBase->query($query_set_unavailable);

            if (!$result_set_unavailable) {
                echo " - (responseToNewOrder) set_unavailable query error - ";
                return;
            }

            // clear candidates list
            $query_clear_list = "UPDATE togo.deliverycandidates SET isDeleted = 1 WHERE orderId = '$orderId'";
            $result_clear_list = $this->dataBase->query($query_clear_list);

            if (!$result_clear_list) {
                echo " - (responseToNewOrder) clear_list query error - ";
                return;
            }

            echo " - offer accepted successfully - ";
        } else {
            echo " - (responseToNewOrder) unknown response error - ";
        }
    }

    // active Order
    public function pickupOrder($orderId)
    {
        $query_get_order_info = "SELECT engin.order_status FROM togo.orderbidengin AS engin WHERE engin.id = '$orderId'";
        $result_get_order_info = $this->dataBase->query($query_get_order_info);

        if (!$result_get_order_info) {
            echo " - (pickupOrder) get_order_info query error - ";
            return;
        }

        $row_count_get_order_info = $this->dataBase->numRows($result_get_order_info);

        if ($row_count_get_order_info == 0) {
            echo " - (pickupOrder) order [" . $orderId . "] not found error - ";
            return;
        }

        $row_get_order_info = $this->dataBase->fetchArray($result_get_order_info);
        $order_status = $row_get_order_info['order_status'];

        if ($order_status != "Bid Accepted") {
            echo " - (pickupOrder) order [" . $orderId . "] is [" . $order_status . "] error - ";
            return;
        }

        $pickup_time = date("Y-m-d H:i:s");
        $query_pickup_order = "UPDATE togo.orderbidengin SET order_status = 'Out for Delivery', pickup_date = '$pickup_time', last_action = 'order picked up' WHERE id = '$orderId'";
        $result_pickup_order = $this->dataBase->query($query_pickup_order);

        if (!$result_pickup_order) {
            echo " - (pickupOrder) pickup_order query error - ";
            return;
        }

        echo " - (pickupOrder) order [" . $orderId . "] picked up successfully - ";
    }

    // active Order
    public function finishOrder($orderId)
    {
        $query_get_order_info = "SELECT engin.order_status FROM togo.orderbidengin AS engin WHERE engin.id = '$orderId'";
        $result_get_order_info = $this->dataBase->query($query_get_order_info);

        if (!$result_get_order_info) {
            echo " - (finishOrder) get_order_info query error - ";
            return;
        }

        $row_count_get_order_info = $this->dataBase->numRows($result_get_order_info);

        if ($row_count_get_order_info == 0) {
            echo " - (finishOrder) order [" . $orderId . "] not found error - ";
            return;
        }

        $row_get_order_info = $this->dataBase->fetchArray($result_get_order_info);
        $order_status = $row_get_order_info['order_status'];

        if ($order_status != "Out for Delivery") {

            if ($order_status == "Bid Accepted") {
                $order_status = "New Order";
            }

            echo " - (finishOrder) order [" . $orderId . "] is [" . $order_status . "] error - ";
            return;
        }

        // get receiver phone

        $query_get_receiver_phone = "SELECT receiver.phone_number AS receiver_phone
        FROM togo.orderbidengin AS engin
        INNER JOIN togo.orderbidaddress AS addresses ON engin.id = addresses.IdOrderBidEngin
        INNER JOIN togo.addresses AS receiver ON addresses.ReciverAddressId = receiver.id
        WHERE engin.id = '$orderId'";

        $result_get_receiver_phone = $this->dataBase->query($query_get_receiver_phone);

        if (!$result_get_receiver_phone) {
            echo " - (finishOrder) get_receiver_phone query error - ";
            return;
        }

        $row_count_get_receiver_phone = $this->dataBase->numRows($result_get_receiver_phone);

        if ($row_count_get_receiver_phone == 0) {
            echo " - (finishOrder) receiver not found error - ";
            return;
        }

        $row_get_receiver_phone = $this->dataBase->fetchArray($result_get_receiver_phone);
        $receiver_phone = $row_get_receiver_phone['receiver_phone'];

        // verification code

        $verification_code = mt_rand(1000, 9999);

        // insert code

        $query_insert_code = "UPDATE togo.orderbidaddress SET CodeVerifyReciver = '$verification_code' WHERE IdOrderBidEngin = '$orderId'";
        $result_insert_code = $this->dataBase->query($query_insert_code);

        if (!$result_insert_code) {
            echo " - (finishOrder) insert_code query error - ";
            return;
        }

        // send SMS

        $message = " الرجاء تزويد هذا الرقم للناقل لتأكيد الإستلام" . $verification_code;

        $result = $this->sendSMS($receiver_phone, $message);

        if ($result == "message not sent") {
            echo " - (finishOrder) message not sent error - ";
            return;
        }

        $query_record_verification_code = "INSERT INTO togo.verifycodestable (code, mobile, description) VALUES ('$verification_code', '$receiver_phone', 'Finish Order')";
        $result_record_verification_code = $this->dataBase->query($query_record_verification_code);

        if (!$result_record_verification_code) {
            echo " - (finishOrder) record_verification_code query error - ";
            return;
        }

        echo " - (finishOrder) verification code sent successfully - ";

        $title = "Order Finished";
        $message = "Order " . $orderId . " finished";
        $this->recordAction($orderId, $title, $message);
    }

    // active Order
    public function confirmFinishOrder($transporterCustomerId, $orderId/* , $enteredCode */)
    {
        /* $query_get_sent_verification_code = "SELECT CodeVerifyReciver AS sent_verification_code FROM togo.orderbidaddress WHERE IdOrderBidEngin = '$orderId'";
        $result_get_sent_verification_code = $this->dataBase->query($query_get_sent_verification_code);

        if (!$result_get_sent_verification_code) {
            echo " - (confirmFinishOrder) get_sent_verification_code query error - ";
            return;
        }

        $row_count_get_sent_verification_code = $this->dataBase->numRows($result_get_sent_verification_code);

        if ($row_count_get_sent_verification_code == 0) {
            echo " - (confirmFinishOrder) sent verification code not found error - ";
            return;
        }

        $row_get_sent_verification_code = $this->dataBase->fetchArray($result_get_sent_verification_code);
        $sent_verification_code = $row_get_sent_verification_code['sent_verification_code'];

        if ($enteredCode != $sent_verification_code) {
            echo " - (confirmFinishOrder) wrong code error - ";
            return;
        } */

        // confirm finish order

        // get merchant's id, cod, and delivery cost
        $query_get_merchant_id = "SELECT 
        engin.CustomerId AS merchant_id,
        engin.CostLoad AS cod,
        bids.CostDelivery AS delivery_cost
        FROM togo.orderbidengin AS engin
        LEFT OUTER JOIN togo.deliveryacceptordertable AS bids ON engin.id = bids.IdOrder AND bids.IdTransporter = '$transporterCustomerId'
        WHERE engin.id = '$orderId'";
        $result_get_merchant_id = $this->dataBase->query($query_get_merchant_id);

        if (!$result_get_merchant_id) {
            echo " - (confirmFinishOrder) get_merchant_id query error - ";
            return;
        }

        $row_count_get_merchant_id = $this->dataBase->numRows($result_get_merchant_id);

        if ($row_count_get_merchant_id == 0) {
            echo " - (confirmFinishOrder) merchant id not found error - ";
            return;
        }

        $row_get_merchant_id = $this->dataBase->fetchArray($result_get_merchant_id);
        $merchant_id = $row_get_merchant_id['merchant_id'];
        $cod = $row_get_merchant_id['cod'];
        $delivery_cost = $row_get_merchant_id['delivery_cost'];

        $finish_time = date("Y-m-d H:i:s");
        $query_deliver_order = "UPDATE togo.orderbidengin SET Orderfinished = 1, order_status='Delivered', dateFinished='$finish_time' WHERE id = '$orderId'";
        $result_deliver_order = $this->dataBase->query($query_deliver_order);

        if (!$result_deliver_order) {
            echo " - (confirmFinishOrder) deliver_order query error - ";
            return;
        }

        // financial

        require_once('OdooService.php');
        $odooService = new OdooService();
        $result_release_delivery_cost = $odooService->releaseEscrow($transporterCustomerId, $orderId, $delivery_cost, 15);
        $result_release_cod = $odooService->releaseEscrow($merchant_id, $orderId, $cod, 1);

        // set transporter as available (isAvailable = 1)

        $query_set_unavailable = "UPDATE togo.transportertable SET isAvailable = 1 WHERE CustomerId = '$transporterCustomerId'";
        $result_set_unavailable = $this->dataBase->query($query_set_unavailable);

        if (!$result_set_unavailable) {
            echo " - (confirmFinishOrder) set_unavailable query error - ";
        }

        echo " - (confirmFinishOrder) order [" . $orderId . "] delivered successfully - ";

        $title = "Order Delivered";
        $message = "Order " . $orderId . " Delivered";
        $this->recordAction($orderId, $title, $message);
    }

    // active Order
    public function returnOrder()
    {
        // mark the order as returned

        echo "returnOrder";
    }

    // active Order
    public function finishReturnedOrder()
    {

        // (confirm) return order to client restaurant

        echo "finishReturnedOrder";
    }



    // ------------------------- (general functions) -------------------------

    public function recordAction($orderId, $title, $message)
    {
        $query_add_record = "INSERT INTO togo.actionsrecordstb (order_id, title, description, action_id) 
        VALUE ('$orderId', '$title', '$message', 30)";

        $result_add_record = $this->dataBase->query($query_add_record);
    }

    public function sendSMS($numbers, $msg)
    {
        $msg = urlencode($msg);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://sms.zone.ps/API/SendSMS.aspx?id=d597f4679d9e20c1a642d60ea904ef9d&sender=ToGo&to=$numbers&msg=$msg");
        curl_setopt($ch, CURLOPT_HEADER, 0);

        ob_start();
        $reslt_Send = curl_exec($ch);
        ob_end_clean();

        curl_close($ch);

        if ($reslt_Send != "Message Sent Successfully!") {
            return "message not sent";
        }

        return "message sent";
    }

    public function sendFCMNotification($recieversTokens, $recieversWebTokens, $data, $title, $body)
    {
        ob_start();

        require_once(dirname(__FILE__) . '/../FcmExample3/Firebase.php');

        $firebase = new Firebase();

        $firebase->sendNotification($recieversTokens, $data);

        /* if ($recieversTokens != -1) {
            $firebase->sendNotification($recieversTokens, $data);
            $firebase->sendIOSNotification($recieversTokens, $data);
        } */

        // $firebase->sendWebNotification($recieversWebTokens, $data, $title, $body);

        ob_end_clean();
    }
}
