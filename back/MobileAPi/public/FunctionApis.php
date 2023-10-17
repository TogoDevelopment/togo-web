<?php
//error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once '../includes/database.php';
require_once '../includes/Apis.php';

$TOGOApp->setDatabase($database);

// echo $_GET['CheckTypeFunction'];

// echo file_get_contents('php://input');
/* $data = file_get_contents('php://input');
//- >  type function -> name
{
    key: value1,
    ket2: value2
}
$_POST[key] = $data[key]
$_POST['CheckTypeFunction'] = $data['CheckTypeFunction']; */

if (isset($_POST['CheckTypeFunction'])) {
    $TypeFunction = $_POST['CheckTypeFunction'];

    if ($TypeFunction == "GetLanguages") {
        $TOGOApp->GetAllLanguages();
    } else
        if ($TypeFunction == "GetRegions") {

        if (isset($_POST['IdLanguages'])) {
            $IdLang = $_POST['IdLanguages'];
            $TOGOApp->GetAllRegions($IdLang);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetRegionsAndIntroductions") {

        if (isset($_POST['IdLanguages'])) {
            $IdLang = $_POST['IdLanguages'];
            $TOGOApp->GetRegionsAndIntroductions($IdLang);
        } else {
            echo "ParameterError";
        }
    } else
            if ($TypeFunction == "deleteodoo") {
        $TOGOApp->deleteodoo();
    } else
                if ($TypeFunction == "GetPostRegions") {

        if (isset($_POST['IdRegion'])) {
            $IdReg = $_POST['IdRegion'];
            $TOGOApp->GetPostRegions($IdReg);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "Login") {

        if (isset($_POST['PhoneNumber']) && isset($_POST['TypeCustomer'])) {
            $PhoneNumber = $_POST['PhoneNumber'];
            $TypeCustomer = $_POST['TypeCustomer'];
            $TOGOApp->Login($PhoneNumber, $TypeCustomer);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "mobileLogout") {

        if (isset($_POST['userId']) && isset($_POST['token'])) {
            $userId = $_POST['userId'];
            $token = $_POST['token'];
            $TOGOApp->mobileLogout($userId, $token);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "VerifiedAcount") {

        if (isset($_POST['PhoneNumber']) && isset($_POST['Code']) && isset($_POST['TokenNotifiy']) && isset($_POST['TokenDevice']) && isset($_POST['RegionId']) && isset($_POST['LangId'])) {
            $PhoneNumber = $_POST['PhoneNumber'];
            $Code = $_POST['Code'];
            $TokenNotifiy = $_POST['TokenNotifiy'];
            $TokenDevice = $_POST['TokenDevice'];
            $RegionId = $_POST['RegionId'];
            $LangId = $_POST['LangId'];
            $TOGOApp->VerifiedAcount($PhoneNumber, $Code, $TokenNotifiy, $TokenDevice, $RegionId, $LangId);
        } else {
            echo "ParameterError";
        }
    } else
                            if ($TypeFunction == "SetType") {
        if (isset($_POST['TypeCustomer']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $TypeCustomer = $_POST['TypeCustomer'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->SetTypeCustomer($CustomerId, $TypeCustomer, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                if ($TypeFunction == "SetPersonalInfo") {
        if (isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['IdClient']) && isset($_POST['Email']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $FirstName = $_POST['FirstName'];
            $LastName = $_POST['LastName'];
            $IdClient = $_POST['IdClient'];
            $Email = $_POST['Email'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->SetPersonalInfo($CustomerId, $FirstName, $LastName, $IdClient, $Email, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                    if ($TypeFunction == "SetBusinessInfo") {
        if (isset($_POST['BusinessName']) && isset($_POST['BusinessPlace']) && isset($_POST['CustomerId']) && isset($_POST['BusinessType']) && isset($_POST['ImgName']) && isset($_POST['ImgCode']) && isset($_POST['TokenDevice'])) {
            $BusinessName = $_POST['BusinessName'];
            $BusinessPlace = $_POST['BusinessPlace'];
            $CustomerId = $_POST['CustomerId'];
            $BusinessType = $_POST['BusinessType'];
            $ImgName = $_POST['ImgName'];
            $ImgCode = $_POST['ImgCode'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->SetBusinessInfo($CustomerId, $BusinessName, $BusinessPlace, $BusinessType, $ImgName, $ImgCode, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetTypeBusiness") {
        if (isset($_POST['Idlanguage']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $Idlanguage = $_POST['Idlanguage'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->GetTypeBusiness($Idlanguage, $CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetCarInfo") {
        if (isset($_POST['Idlanguage']) && isset($_POST['TransporterId']) && isset($_POST['TokenDevice'])) {
            $Idlanguage = $_POST['Idlanguage'];
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->GetCarInfo($Idlanguage, $TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateDeliveryTypes") {
        if (isset($_POST['TransporterId']) && isset($_POST['deliveryTypes']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->updateDeliveryTypes($_POST['TransporterId'], $_POST['deliveryTypes'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetIdPlaceLicence") {
        if (isset($_POST['Idlanguage']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $Idlanguage = $_POST['Idlanguage'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->GetIdPlaceLicence($Idlanguage, $CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "SetTransporterInfo") {
        if (
            isset($_POST['CustomerId']) && isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['BirthDay']) && isset($_POST['IDPlace']) &&
            isset($_POST['IDNumber']) && isset($_POST['LicenceNumber']) && isset($_POST['LicenceType']) &&
            isset($_POST['Email']) && isset($_POST['AccountName']) && isset($_POST['PersonalImgName']) &&
            isset($_POST['PersonalImgCode']) && isset($_POST['LicenceImgName']) && isset($_POST['LicenceImgCode']) && isset($_POST['TokenDevice']) && isset($_POST['isFoodTrans'])
        ) {
            $CustomerId = $_POST['CustomerId'];
            $FirstName = $_POST['FirstName'];
            $LastName = $_POST['LastName'];
            $BirthDay = $_POST['BirthDay'];
            $IDPlace = $_POST['IDPlace'];
            $IDNumber = $_POST['IDNumber'];
            $LicenceNumber = $_POST['LicenceNumber'];
            $LicenceType = $_POST['LicenceType'];
            $Email = $_POST['Email'];
            $AccountName = $_POST['AccountName'];
            $PersonalImgName = $_POST['PersonalImgName'];
            $PersonalImgCode = $_POST['PersonalImgCode'];
            $LicenceImgName = $_POST['LicenceImgName'];
            $LicenceImgCode = $_POST['LicenceImgCode'];
            $TokenDevice = $_POST['TokenDevice'];
            $isFoodTrans = $_POST['isFoodTrans'];

            $TOGOApp->SetTransporterInfo($CustomerId, $FirstName, $LastName, $BirthDay, $IDPlace, $IDNumber, $LicenceNumber, $LicenceType, $Email, $AccountName, $PersonalImgName, $PersonalImgCode, $LicenceImgName, $LicenceImgCode, $TokenDevice, $isFoodTrans);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "SetCarInfo") {
        if (
            isset($_POST['CustomerId']) && isset($_POST['RegistrationNumber']) && isset($_POST['RegistrationFinshDay']) && isset($_POST['LicenceCarNumber']) &&
            isset($_POST['CarColorId']) && isset($_POST['CarImgName']) && isset($_POST['CarImgCode']) &&
            isset($_POST['RegistrationImgName']) && isset($_POST['RegistrationImgCode']) && isset($_POST['CarImgId']) && isset($_POST['deliveryTypes']) && isset($_POST['TokenDevice'])
        ) {
            $CustomerId = $_POST['CustomerId'];
            $RegistrationNumber = $_POST['RegistrationNumber'];
            $RegistrationFinshDay = $_POST['RegistrationFinshDay'];
            $LicenceCarNumber = $_POST['LicenceCarNumber'];
            $CarColorId = $_POST['CarColorId'];
            $CarImgName = $_POST['CarImgName'];
            $CarImgCode = $_POST['CarImgCode'];
            $RegistrationImgName = $_POST['RegistrationImgName'];
            $RegistrationImgCode = $_POST['RegistrationImgCode'];
            $CarImgId = $_POST['CarImgId'];
            $deliveryTypes = $_POST['deliveryTypes'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->SetCarInfo($CustomerId, $RegistrationNumber, $RegistrationFinshDay, $LicenceCarNumber, $CarColorId, $CarImgName, $CarImgCode, $RegistrationImgName, $RegistrationImgCode, $CarImgId, $deliveryTypes, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetColorPhotoCar") {
        if (isset($_POST['Idlanguage']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $Idlanguage = $_POST['Idlanguage'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->GetColorPhotoCar($Idlanguage, $CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetCityRegion") {
        if (isset($_POST['Idlanguage']) && isset($_POST['IdRegion']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $Idlanguage = $_POST['Idlanguage'];
            $IdRegion = $_POST['IdRegion'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->GetCityRegion($Idlanguage, $IdRegion, $CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } //diala
    else if ($TypeFunction == "GetRegDiscountValue") {
        if (isset($_POST['IdRegion'])) {

            $IdRegion = $_POST['IdRegion'];

            $TOGOApp->GetRegDiscountValue($IdRegion);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "cancellationValue") {
        if (isset($_POST['regionId'])) {

            $IdRegion = $_POST['regionId'];


            $TOGOApp->cancellationValue($IdRegion);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientInvoices") {
        if (isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getClientInvoices($CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterInvoices") {
        if (isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getTransporterInvoices($CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } //by diala for test issues
    else if ($TypeFunction == "addInvoice") {

        if (isset($_POST['orderid'])) {
            $orderid = $_POST['orderid'];
            $amount = 100;
            $tax = 10;
            $togodis = 10;
            $date = "2020";

            $TOGOApp->addInvoice($amount, $tax, $togodis, $date, $orderid);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getdiscountvalue") {

        if (isset($_POST['orderid'])) {
            $orderid = $_POST['orderid'];

            $TOGOApp->getdiscountvalue($orderid);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "SetWorkTranspoterParameter") {
        if (isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->SetWorkTranspoterParameter($CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetCityPhotosBidEngin") {
        if (isset($_POST['Idlanguage']) && isset($_POST['CustomerId']) && isset($_POST['IdRegion']) && isset($_POST['TokenDevice'])) {
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $Idlanguage = $_POST['Idlanguage'];
            $IdRegion = $_POST['IdRegion'];
            $TOGOApp->GetCityPhotosBidEngin($CustomerId, $TokenDevice, $Idlanguage, $IdRegion);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "OrderBidEnginParams") {
        if (isset($_POST['CreatedBy']) && isset($_POST['DeliveryParams']) && isset($_POST['AddressClint']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $DeliveryParams = $_POST['DeliveryParams'];
            $AddressClint = $_POST['AddressClint'];
            $isNewAddress = $_POST['isNewAddress'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $CreatedBy = $_POST['CreatedBy'];
            $DeliveryParams = json_decode($DeliveryParams, true);
            $AddressClint = json_decode($AddressClint, true);
            if ($CreatedBy == "Transporter") {
                $TOGOApp->OrderBidEnginParamsTransporter($DeliveryParams, $AddressClint, $CustomerId, $TokenDevice);
            }
            if ($CreatedBy == "Client") {
                $TOGOApp->OrderBidEnginParams($DeliveryParams, $AddressClint, $CustomerId, $TokenDevice);
            }
            if ($CreatedBy == "ClientNew") {
                $TOGOApp->OrderBidEnginParamsClient($DeliveryParams, $AddressClint, $isNewAddress, $CustomerId, $TokenDevice);
            }
            if ($CreatedBy == "ClientTest") {
                $TOGOApp->OrderBidEnginParamsClientTest($DeliveryParams, $AddressClint, $isNewAddress, $CustomerId, $TokenDevice);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "createNewLogestechsExclusiveClientOrder") {
        if (isset($_POST['deliveryParams']) && isset($_POST['addresses']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice']) && isset($_POST['isNewAddress'])) {
            $deliveryParams = $_POST['deliveryParams'];
            $addresses = $_POST['addresses'];
            $isNewAddress = $_POST['isNewAddress'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $consignmentNo = $_POST['consignmentNo'];
            $deliveryParams = json_decode($deliveryParams, true);
            $addresses = json_decode($addresses, true);

            $TOGOApp->createNewLogestechsExclusiveClientOrder($deliveryParams, $addresses, $isNewAddress, $CustomerId, $TokenDevice, $consignmentNo);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterGetOrder") {
        if (isset($_POST['TransporterId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $TOGOApp->TransporterGetOrder($TransporterId, $TokenDevice, $PageSize, $PageNumber);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ShowBidRequistsDetails") {
        if (isset($_POST['OrderId']) && isset($_POST['TokenDevice'])) {
            $OrderId = $_POST['OrderId'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->ShowBidRequistsDetails($OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterSetCostOrder") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['CostDelivery']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $OrderId = $_POST['OrderId'];
            $CostDelivery = $_POST['CostDelivery'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->TransporterSetCostOrder($TransporterId, $OrderId, $CostDelivery, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ShowClientOrder") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->ShowClientOrder($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr);
            } else {
                $TOGOApp->ShowClientOrder($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str");
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientShowBidRequists") {
        if (isset($_POST['ClientId']) && isset($_POST['OrderId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $OrderId = $_POST['OrderId'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->ClientShowBidRequists($ClientId, $OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientShowBidRequistsAndNetwork") { // edited from the above, adding client network as offers to assign to
        if (isset($_POST['ClientId']) && isset($_POST['OrderId']) && isset($_POST['DeliveryCost']) && isset($_POST['TokenDevice']) && isset($_POST['fromId']) && isset($_POST['toId'])) {
            $ClientId = $_POST['ClientId'];
            $OrderId = $_POST['OrderId'];
            $DeliveryCost = $_POST['DeliveryCost'];
            $TokenDevice = $_POST['TokenDevice'];
            $fromId = $_POST['fromId'];
            $toId = $_POST['toId'];

            $TOGOApp->ClientShowBidRequistsAndNetwork($ClientId, $OrderId, $DeliveryCost, $TokenDevice, $fromId, $toId);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "AdminCheckTripCost") { // edited from the above, adding client network as offers to assign to
        if (isset($_POST['ClientId']) && isset($_POST['OrderId']) && isset($_POST['DeliveryCost']) && isset($_POST['adminToken']) && isset($_POST['adminId']) && isset($_POST['fromId']) && isset($_POST['toId'])) {
            $ClientId = $_POST['ClientId'];
            $OrderId = $_POST['OrderId'];
            $DeliveryCost = $_POST['DeliveryCost'];
            $adminToken = $_POST['adminToken'];
            $adminId = $_POST['adminId'];
            $fromId = $_POST['fromId'];
            $toId = $_POST['toId'];

            $TOGOApp->AdminCheckTripCost($ClientId, $OrderId, $DeliveryCost, $adminId, $adminToken);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientShowBidRequistsAccepted") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->ClientShowBidRequistsAccepted($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr);
            } else {
                $TOGOApp->ClientShowBidRequistsAccepted($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str");
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveClientActiveOrdersByPage") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber']) && isset($_POST['langId'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $langId = $_POST['langId'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getExclusiveClientActiveOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr, $langId);
            } else {
                $TOGOApp->getExclusiveClientActiveOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str", $langId);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveClientFinishedDeletedOrdersByPage") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber']) && isset($_POST['langId'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $langId = $_POST['langId'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getExclusiveClientFinishedDeletedOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr, $langId);
            } else {
                $TOGOApp->getExclusiveClientFinishedDeletedOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str", $langId);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveTransporterActiveOrdersByPage") {
        if (isset($_POST['transporterId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber']) && isset($_POST['langId'])) {
            $transporterId = $_POST['transporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $langId = $_POST['langId'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getExclusiveTransporterActiveOrdersByPage($transporterId, $TokenDevice, $PageSize, $PageNumber, $searchStr, $langId);
            } else {
                $TOGOApp->getExclusiveTransporterActiveOrdersByPage($transporterId, $TokenDevice, $PageSize, $PageNumber, "no_str", $langId);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveTransporterFinishedDeletedOrdersByPage") {
        if (isset($_POST['transporterId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber']) && isset($_POST['langId'])) {
            $transporterId = $_POST['transporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $langId = $_POST['langId'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getExclusiveTransporterFinishedDeletedOrdersByPage($transporterId, $TokenDevice, $PageSize, $PageNumber, $searchStr, $langId);
            } else {
                $TOGOApp->getExclusiveTransporterFinishedDeletedOrdersByPage($transporterId, $TokenDevice, $PageSize, $PageNumber, "no_str", $langId);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveTransporterReviewedOrdersByPage") {
        if (isset($_POST['transporterId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber']) && isset($_POST['langId'])) {
            $transporterId = $_POST['transporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $langId = $_POST['langId'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getExclusiveTransporterReviewedOrdersByPage($transporterId, $TokenDevice, $PageSize, $PageNumber, $searchStr, $langId);
            } else {
                $TOGOApp->getExclusiveTransporterReviewedOrdersByPage($transporterId, $TokenDevice, $PageSize, $PageNumber, "no_str", $langId);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "AcceptClientBidEngie") {
        if (isset($_POST['ClientId']) && isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $TransporterId = $_POST['TransporterId'];
            $OldPrice = $_POST['OldPrice'];
            $OrderId = $_POST['OrderId'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->AcceptClientBidEngie($ClientId, $TransporterId, $OldPrice, $OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "AdminAcceptOfferReq") {
        if (isset($_POST['ClientId']) && isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['adminToken']) && isset($_POST['adminId'])) {
            $ClientId = $_POST['ClientId'];
            $TransporterId = $_POST['TransporterId'];
            $OldPrice = $_POST['OldPrice'];
            $OrderId = $_POST['OrderId'];
            $adminToken = $_POST['adminToken'];
            $adminId = $_POST['adminId'];

            $TOGOApp->AdminAcceptOfferReq($ClientId, $TransporterId, $OldPrice, $OrderId, $adminToken, $adminId);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterShowDetailsOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['TransporterId']) && isset($_POST['TokenDevice'])) {

            $OrderId = $_POST['OrderId'];
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterShowDetailsOrder($OrderId, $TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "checkForForeignId") {
        if (isset($_POST['OrderId']) && isset($_POST['customerId']) && isset($_POST['TokenDevice'])) {

            $OrderId = $_POST['OrderId'];
            $TransporterId = $_POST['customerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->checkForForeignId($OrderId, $TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "checkForForeignIdForAdmin") {
        if (isset($_POST['OrderId']) && isset($_POST['adminId']) && isset($_POST['AdminToken'])) {

            $OrderId = $_POST['OrderId'];
            $adminId = $_POST['adminId'];
            $AdminToken = $_POST['AdminToken'];
            $TOGOApp->checkForForeignIdForAdmin($OrderId, $adminId, $AdminToken);
        } else {
            echo "ParameterError";
        }
    } else  if ($TypeFunction == "getOrderDetailsForAdmin") { /* edited (get order details for admin) */
        if (isset($_POST['OrderId']) && isset($_POST['Adminid']) && isset($_POST['AdminToken'])) {

            $OrderId = $_POST['OrderId'];
            $adminId = $_POST['Adminid'];
            $TokenDevice = $_POST['AdminToken'];
            $TOGOApp->getOrderDetailsForAdmin($OrderId, $adminId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterShowDetailsOrderCurrent") {
        if (isset($_POST['OrderId']) && isset($_POST['TransporterId']) && isset($_POST['TokenDevice'])) {

            $OrderId = $_POST['OrderId'];
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterShowDetailsOrderCurrent($OrderId, $TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientShowDetailsOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['ClientId']) && isset($_POST['TokenDevice'])) {
            $OrderId = $_POST['OrderId'];
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientShowDetailsOrder($OrderId, $ClientId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientShowDetailsOrderCurrent") {
        if (isset($_POST['OrderId']) && isset($_POST['ClientId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $OrderId = $_POST['OrderId'];
            $ClientId = $_POST['ClientId'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientShowDetailsOrderCurrent($OrderId, $ClientId, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "showExclusiveLogestechsOrderDetails") {
        if (isset($_POST['OrderId']) && isset($_POST['ClientId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $OrderId = $_POST['OrderId'];
            $ClientId = $_POST['ClientId'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->showExclusiveLogestechsOrderDetails($OrderId, $ClientId, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientDeleteOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['ClientId']) && isset($_POST['TokenDevice'])) {
            $OrderId = $_POST['OrderId'];
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientDeleteOrder($OrderId, $ClientId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientHistoryOrder") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->ClientHistoryOrder($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr);
            } else {
                $TOGOApp->ClientHistoryOrder($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str");
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientReviewedOrder") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->ClientReviewedOrder($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr);
            } else {
                $TOGOApp->ClientReviewedOrder($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str");
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveClientReviewedOrdersByPage") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getExclusiveClientReviewedOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr);
            } else {
                $TOGOApp->getExclusiveClientReviewedOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str");
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveTransporterReviewedOrdersByPage") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getExclusiveTransporterReviewedOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, $searchStr);
            } else {
                $TOGOApp->getExclusiveTransporterReviewedOrdersByPage($ClientId, $TokenDevice, $PageSize, $PageNumber, "no_str");
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientDeliveredTotalAmounts") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getClientDeliveredTotalAmounts($ClientId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientActiveTotalAmounts") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getClientActiveTotalAmounts($ClientId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientNewTotalAmounts") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getClientNewTotalAmounts($ClientId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterDeliveredTotalAmounts") {
        if (isset($_POST['TransporterId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getTransporterDeliveredTotalAmounts($TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterActiveTotalAmounts") {
        if (/* isset($_POST['TransporterId']) && isset($_POST['TokenDevice']) */true) {
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getTransporterActiveTotalAmounts($TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterNewTotalAmounts") {
        if (isset($_POST['TransporterId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->getTransporterNewTotalAmounts($TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterHistoryOrder") {
        if (isset($_POST['TransporterId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $TOGOApp->TransporterHistoryOrder($TransporterId, $TokenDevice, $PageSize, $PageNumber);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterOrderCurrent") {
        if (isset($_POST['TransporterId']) && isset($_POST['TokenDevice']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $PageSize = $_POST['PageSize'];
            $PageNumber = $_POST['PageNumber'];
            $TOGOApp->TransporterOrderCurrent($TransporterId, $TokenDevice, $PageSize, $PageNumber);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterDeleteOrder") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $OrderId = $_POST['OrderId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterDeleteOrder($TransporterId, $OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterSetLocationCurrentOrders") {
        if (isset($_POST['TransporterId']) && isset($_POST['TransporterLatLocation']) && isset($_POST['TransporterLongLocation']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $TransporterLatLocation = $_POST['TransporterLatLocation'];
            $TransporterLongLocation = $_POST['TransporterLongLocation'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterSetLocationCurrentOrders($TransporterId, $TransporterLatLocation, $TransporterLongLocation, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                                                                                                                                    if ($TypeFunction == "TeamMemberSetLocationCurrentOrders") {
        if (isset($_POST['TransporterId']) && isset($_POST['TransporterLatLocation']) && isset($_POST['TransporterLongLocation']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $TransporterLatLocation = $_POST['TransporterLatLocation'];
            $TransporterLongLocation = $_POST['TransporterLongLocation'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TeamMemberSetLocationCurrentOrders($TransporterId, $TransporterLatLocation, $TransporterLongLocation, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientTrackTransporterLocation") {
        if (isset($_POST['ClientId']) && isset($_POST['OrderId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $OrderId = $_POST['OrderId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientTrackTransporterLocation($ClientId, $OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterFinshTrip") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $OrderId = $_POST['OrderId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterFinshTrip($TransporterId, $OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterConfirmFinshTrip") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['CodeVerify']) && isset($_POST['TokenDevice']) && isset($_POST['ReciverLatitude']) && isset($_POST['ReciverLongitude'])) {
            $TransporterId = $_POST['TransporterId'];
            $OrderId = $_POST['OrderId'];
            $CodeVerify = $_POST['CodeVerify'];
            $TokenDevice = $_POST['TokenDevice'];
            $ReciverLatitude = $_POST['ReciverLatitude'];
            $ReciverLongitude = $_POST['ReciverLongitude'];
            $isTeamMember = $_POST['isTeamMember'];
            $TOGOApp->TransporterConfirmFinshTrip($TransporterId, $OrderId, $CodeVerify, $TokenDevice, $ReciverLatitude, $ReciverLongitude);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "forcePickup") {
        if (isset($_POST['OrderId']) && isset($_POST['adminId']) && isset($_POST['TokenDevice'])) {
            $OrderId = $_POST['OrderId'];
            $adminId = $_POST['adminId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->forcePickup($OrderId, $adminId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "forceFinishOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['adminId']) && isset($_POST['TokenDevice'])) {
            $OrderId = $_POST['OrderId'];
            $adminId = $_POST['adminId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->forceFinishOrder($OrderId, $adminId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterMemberFinishTrip") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['CodeVerify']) && isset($_POST['TokenDevice']) && isset($_POST['ReciverLatitude']) && isset($_POST['ReciverLongitude'])) {
            $TransporterId = $_POST['TransporterId'];
            $OrderId = $_POST['OrderId'];
            $CodeVerify = $_POST['CodeVerify'];
            $TokenDevice = $_POST['TokenDevice'];
            $ReciverLatitude = $_POST['ReciverLatitude'];
            $ReciverLongitude = $_POST['ReciverLongitude'];
            $isTeamMember = $_POST['isTeamMember'];
            $TOGOApp->TransporterMemberFinishTrip($TransporterId, $OrderId, $CodeVerify, $TokenDevice, $ReciverLatitude, $ReciverLongitude);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientRateTrip") {
        if (isset($_POST['ClientId']) && isset($_POST['OrderId']) && isset($_POST['RateValue']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $OrderId = $_POST['OrderId'];
            $RateValue = $_POST['RateValue'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientRateTrip($ClientId, $OrderId, $RateValue, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "CheckPriceTrip") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $OrderId = $_POST['OrderId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->CheckPriceTrip($TransporterId, $OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterEditPriceTrip") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['NewCost']) && isset($_POST['TypeAction']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $OrderId = $_POST['OrderId'];
            $NewCost = $_POST['NewCost'];
            $TypeAction = $_POST['TypeAction'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterEditPriceTrip($TransporterId, $OrderId, $NewCost, $TypeAction, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetBalanceTransporter") {
        if (isset($_POST['TransporterId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->GetBalanceTransporter($TransporterId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "testGetBalance") {
        if (isset($_POST['customer_id'])) {
            $customer_id = $_POST['customer_id'];
            $TOGOApp->testGetBalance($customer_id);
        } else {
            echo "ParameterError";
        }
    }  //by diala
    else if ($TypeFunction == "chargeBalance") {
        if (isset($_POST['CustomerId']) && isset($_POST['TokenDevice']) && isset($_POST['chargeAmount'])) {
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $chargeAmount = $_POST['chargeAmount'];
            $TOGOApp->chargeBalance($CustomerId, $TokenDevice, $chargeAmount);
        } else {
            echo "ParameterError";
        }
    } // Profile Client And Transporter
    else if ($TypeFunction == "ClientEditPersonalInfo") {
        if (isset($_POST['ClientId']) && isset($_POST['Name']) && isset($_POST['Email']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $Name = $_POST['Name'];
            $Email = $_POST['Email'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientTrackTransporterLocation($ClientId, $OrderId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientProfileGetDataTypeWork") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientProfileGetDataTypeWork($ClientId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientProfileGetTypeWork") {
        if (isset($_POST['ClientId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientProfileGetTypeWork($ClientId, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientProfileEditWorkInfo") {
        if (isset($_POST['ClientId']) && isset($_POST['WorkName']) && isset($_POST['WorkPlace']) && isset($_POST['WorkTypeId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $WorkName = $_POST['WorkName'];
            $WorkPlace = $_POST['WorkPlace'];
            $WorkTypeId = $_POST['WorkTypeId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientProfileEditWorkInfo($ClientId, $WorkName, $WorkPlace, $WorkTypeId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientProfileViewPersonalInfo") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $TokenDevice = $_POST['TokenDevice'];

            $TOGOApp->ClientProfileViewPersonalInfo($ClientId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterProfileViewPersonalinfo") {
        if (isset($_POST['TransporterId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterProfileEditPersonalinfo($TransporterId, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterProfileViewCityinfo") {
        if (isset($_POST['TransporterId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterProfileViewCityinfo($TransporterId, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransporterUpdateProfileCityinfo") {
        if (isset($_POST['TransporterId']) && isset($_POST['CityId']) && isset($_POST['CheckAction']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $CityId = $_POST['CityId'];
            $CheckAction = $_POST['CheckAction'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterUpdateProfileCityinfo($TransporterId, $CityId, $CheckAction, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                                                                                                                                                                                                        if ($TypeFunction == "TransporterProfileViewTimeinfo") {
        if (isset($_POST['TransporterId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $TransporterId = $_POST['TransporterId'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->TransporterProfileViewTimeinfo($TransporterId, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                                                                                                                                                                                                            if ($TypeFunction == "ClientGetLastLocationSaved") {
        if (isset($_POST['ClientId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $ClientId = $_POST['ClientId'];
            $LangId = $_POST['LangId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ClientGetLastLocationSaved($ClientId, $LangId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                                                                                                                                                                                                                if ($TypeFunction == "UpdateWorkTranspoterParameter") {
        if (isset($_POST['TimeWork']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $TimeWork = $_POST['TimeWork'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];

            $myarrayTime = json_decode($TimeWork, true);
            $TOGOApp->UpdateWorkTranspoterParameter($myarrayTime, $CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                                                                                                                                                                                                                    if ($TypeFunction == "RecendCode") {
        if (isset($_POST['PhoneNumber'])) {
            $PhoneNumber = $_POST['PhoneNumber'];

            $TOGOApp->RecendCode($PhoneNumber);
        } else {
            echo "ParameterError";
        }
    } else
                                                                                                                                                                                                                                        if ($TypeFunction == "ChangeNumber") {
        if (isset($_POST['PhoneNumber']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $PhoneNumber = $_POST['PhoneNumber'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            $TOGOApp->ChangeNumber($PhoneNumber, $CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else
                                                                                                                                                                                                                                            if ($TypeFunction == "GetInfoCustomer") {
        if (isset($_POST['TypeCustomer'])) {
            $TypeCustomer = $_POST['TypeCustomer'];
            $TOGOApp->TypeCustomerInfo($TypeCustomer);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GenerateUUID") {
        $TOGOApp->GenerateUUID();
    } else if ($TypeFunction == "setUserCredentials") {
        if (isset($_POST['QRId']) && isset($_POST['CustomerId']) && isset($_POST['TokenDevice'])) {
            $QRId = $_POST['QRId'];
            $CustomerId = $_POST['CustomerId'];
            $TokenDevice = $_POST['TokenDevice'];
            echo $QRId;
            $TOGOApp->setUserCredentials($QRId, $CustomerId, $TokenDevice);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getUserCredentials") {
        if (isset($_POST['QRId'])) {
            $QRId = $_POST['QRId'];
            $TOGOApp->getUserCredentials($QRId);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "startPickup") {
        if (isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['TokenDevice']) && isset($_POST['qrCode']) && isset($_POST['SenderLatitude']) && isset($_POST['SenderLongitude'])) {
            $TOGOApp->startPickup($_POST['qrCode'], $_POST['OrderId'], $_POST['TransporterId'], $_POST['TokenDevice'], $_POST['SenderLatitude'], $_POST['SenderLongitude']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "clientCurrentOrders") {
        if (isset($_POST['clientId']) && isset($_POST['TokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getClientCurrentOrders($_POST['clientId'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveClientActiveOrders") {
        if (isset($_POST['clientId']) && isset($_POST['TokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getExclusiveClientActiveOrders($_POST['clientId'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveClientFinishedOrders") {
        if (isset($_POST['clientId']) && isset($_POST['TokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getExclusiveClientFinishedOrders($_POST['clientId'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "clientFinishedOrders") {
        if (isset($_POST['clientId']) && isset($_POST['TokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getClientFinishedOrders($_POST['clientId'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientFinishOrder") {
        if (isset($_POST['ClientId']) && isset($_POST['TokenDevice']) && isset($_POST['OrderId'])) {
            $TOGOApp->ClientFinishOrder($_POST['ClientId'], $_POST['OrderId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "transporterOrders") {
        if (isset($_POST['transporterId']) && isset($_POST['TokenDevice']) && isset($_POST['langId']) && isset($_POST['filter'])) {
            $TOGOApp->getTransporterOrders($_POST['filter'], $_POST['transporterId'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "transporterOrdersTest") {

        $TOGOApp->getTransporterOrdersTest();
    }/*  else if ($TypeFunction == "transporterRelatedOrders") { // edited (get all related orders by the timeline) 
        if (isset($_POST['transporterId']) && isset($_POST['TokenDevice']) && isset($_POST['langId']) && isset($_POST['filter'])) {
            $TOGOApp->getTransporterRelatedOrders($_POST['filter'], $_POST['transporterId'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } */ else if ($TypeFunction == "getTransporterRelatedOrdersByPage") { /* edited (get all related orders by the timeline and by page, for pagination display (for web)) */
        if (isset($_POST['transporterId']) && isset($_POST['TokenDevice']) && isset($_POST['langId']) && isset($_POST['filter']) && isset($_POST['PageSize']) && isset($_POST['PageNumber'])) {

            if (isset($_POST['searchStr'])) {
                $searchStr = $_POST['searchStr'];

                $TOGOApp->getTransporterRelatedOrdersByPage($_POST['filter'], $_POST['transporterId'], $_POST['TokenDevice'], $_POST['langId'], $_POST['PageSize'], $_POST['PageNumber'], $searchStr);
            } else {
                $TOGOApp->getTransporterRelatedOrdersByPage($_POST['filter'], $_POST['transporterId'], $_POST['TokenDevice'], $_POST['langId'], $_POST['PageSize'], $_POST['PageNumber']);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "transporterTeamOrders") {
        if (isset($_POST['transporterId']) && isset($_POST['TokenDevice']) && isset($_POST['langId']) && isset($_POST['filter'])) {
            $TOGOApp->getTransporterTeamOrders($_POST['filter'], $_POST['transporterId'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAddresses") {
        if (isset($_POST['creatorId']) && isset($_POST['searchText']) && isset($_POST['TokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getAddresses($_POST['creatorId'], $_POST['searchText'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getLogestechsExclusiveClientAddresses") {
        if (isset($_POST['creatorId']) && isset($_POST['searchText']) && isset($_POST['TokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getLogestechsExclusiveClientAddresses($_POST['creatorId'], $_POST['searchText'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getPrivateAddresses") {
        if (isset($_POST['creatorId']) && isset($_POST['searchText']) && isset($_POST['TokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getPrivateAddresses($_POST['creatorId'], $_POST['searchText'], $_POST['TokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getSbytaniAddresses") {
        if (isset($_POST['customerId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->getSbytaniAddresses($_POST['customerId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "createNewAddress") {
        if (
            isset($_POST['name']) && isset($_POST['phoneNumber']) && isset($_POST['details']) && isset($_POST['additionalInfo'])
            && isset($_POST['country']) && isset($_POST['zipCode']) && isset($_POST['isShared']) && isset($_POST['customerId'])
            && isset($_POST['creatorId']) && isset($_POST['deviceToken']) && isset($_POST['cityId'])
            && isset($_POST['provinceId'])  && isset($_POST['governorateId'])  && isset($_POST['areaId'])
        ) {
            $TOGOApp->createNewAddress(
                $_POST['name'],
                $_POST['phoneNumber'],
                $_POST['details'],
                $_POST['additionalInfo'],
                $_POST['country'],
                $_POST['zipCode'],
                $_POST['isShared'],
                $_POST['customerId'],
                $_POST['creatorId'],
                $_POST['deviceToken'],
                $_POST['cityId'],
                $_POST['provinceId'],
                $_POST['governorateId'],
                $_POST['areaId']
            );
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "createExclusiveLogestechsAddress") {
        if (
            // addressDetails, addressAditionalInfo, contactPhone, contactName, villageId, cityId, regionId, villageArName, villageEnName, regionName
            isset($_POST['contactName']) &&
            isset($_POST['contactPhone']) &&
            isset($_POST['addressDetails']) &&
            isset($_POST['addressAditionalInfo']) &&
            isset($_POST['customerId']) &&
            isset($_POST['creatorId']) &&
            isset($_POST['deviceToken']) &&
            isset($_POST['cityId']) &&
            isset($_POST['regionId']) &&
            isset($_POST['villageId']) &&
            isset($_POST['villageEnName']) &&
            isset($_POST['villageArName']) &&
            isset($_POST['regionName'])
        ) {
            $TOGOApp->createExclusiveLogestechsAddress(
                $_POST['contactName'],
                $_POST['contactPhone'],
                $_POST['addressDetails'],
                $_POST['addressAditionalInfo'],
                $_POST['customerId'],
                $_POST['creatorId'],
                $_POST['deviceToken'],
                $_POST['cityId'],
                $_POST['regionId'],
                $_POST['villageId'],
                $_POST['villageEnName'],
                $_POST['villageArName'],
                $_POST['regionName']
            );
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientAddresses") {
        if (isset($_POST['clientId']) && isset($_POST['token'])) {
            $TOGOApp->getClientAddresses($_POST['clientId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "createNewTeam") {
        if (isset($_POST['transporterId']) && isset($_POST['teamName']) && isset($_POST['token'])) {
            $TOGOApp->createNewTeam($_POST['transporterId'], $_POST['teamName'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateTeamData") {
        if (isset($_POST['clientId']) && isset($_POST['token'])) {
            $TOGOApp->getClientAddresses($_POST['clientId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "activateTeam") {
        if (isset($_POST['transporterId']) && isset($_POST['verifyCode']) && isset($_POST['token'])) {
            $TOGOApp->activateTeam($_POST['transporterId'], $_POST['verifyCode'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "unsubscribeFromTeam") {
        if (isset($_POST['transporterId']) && isset($_POST['token'])) {
            $TOGOApp->unsubscribeFromTeam($_POST['transporterId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterMasterTeams") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['token'])) {
            $TOGOApp->getTransporterMasterTeams($_POST['transporterMasterId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllTeamsMembers") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['token'])) {
            $TOGOApp->getAllTeamsMembers($_POST['transporterMasterId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTeamMembers") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['TeamId']) && isset($_POST['token'])) {
            $TOGOApp->getTeamMembers($_POST['transporterMasterId'], $_POST['TeamId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "addTeamMember") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['mobileNumber']) && isset($_POST['teamId']) && isset($_POST['token'])) {
            $TOGOApp->addTeamMember($_POST['transporterMasterId'], $_POST['mobileNumber'], $_POST['teamId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "assignOrderToMember") {
        if (isset($_POST['masterId']) && isset($_POST['memberId']) && isset($_POST['orderId']) && isset($_POST['token'])) {
            $TOGOApp->assignOrderToMember($_POST['masterId'], $_POST['memberId'], $_POST['orderId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransferFromMasterToMember") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['teamMemberId']) && isset($_POST['transferAmount']) && isset($_POST['token'])) {
            $TOGOApp->TransferFromMasterToMember($_POST['transporterMasterId'], $_POST['teamMemberId'], $_POST['transferAmount'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TransferFromMemberToMaster") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['teamMemberId']) && isset($_POST['transferAmount']) && isset($_POST['token'])) {
            $TOGOApp->TransferFromMemberToMaster($_POST['transporterMasterId'], $_POST['teamMemberId'], $_POST['transferAmount'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "transferOrderBetweenMembers") {
        if (isset($_POST['transporterId']) && isset($_POST['teamId']) && isset($_POST['qrCode']) && isset($_POST['token'])) {
            $TOGOApp->transferOrderBetweenMembers($_POST['transporterId'], $_POST['teamId'], $_POST['qrCode'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "deleteTeam") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['teamId']) && isset($_POST['token'])) {
            $TOGOApp->deleteTeam($_POST['transporterMasterId'], $_POST['teamId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateTeamName") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['teamId']) && isset($_POST['teamNewName']) && isset($_POST['token'])) {
            $TOGOApp->updateTeamName($_POST['transporterMasterId'], $_POST['teamId'], $_POST['teamNewName'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "deleteTeamMember") {
        if (isset($_POST['transporterMasterId']) && isset($_POST['memberId']) && isset($_POST['token'])) {
            $TOGOApp->deleteTeamMember($_POST['transporterMasterId'], $_POST['memberId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "TeamMemberTransaction") {
        if (isset($_POST['TransporterMasterId']) && isset($_POST['TeamMemberId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->getTeamMemberTransaction($_POST['TransporterMasterId'], $_POST['TeamMemberId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "UpdateCustomerLanguage") {
        if (isset($_POST['CustomerId']) && isset($_POST['LangId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->UpdateCustomerLanguage($_POST['CustomerId'], $_POST['LangId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllNewOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->GetAllNewOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "admin_GetAllNewFoodOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->admin_GetAllNewFoodOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr'])) {
            $TOGOApp->getAllOrders($_POST['id'], $_POST['token'], $_POST['searchStr']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllActiveOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->GetAllActiveOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "admin_GetAllActiveFoodOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->admin_GetAllActiveFoodOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllMarkedOrders") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->GetAllMarkedOrders($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllFinishedOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->GetAllFinishedOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "admin_GetAllFinishedFoodOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->admin_GetAllFinishedFoodOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllDeletedOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->GetAllDeletedOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "admin_GetAllDeletedFoodOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->admin_GetAllDeletedFoodOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllReturnedOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->GetAllReturnedOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllExceptionOrders") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['searchStr']) && isset($_POST['filterDate'])) {
            $TOGOApp->GetAllExceptionOrders($_POST['id'], $_POST['token'], $_POST['searchStr'], $_POST['filterDate']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllTransporters") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->GetAllTransporters($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllClients") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->GetAllClients($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllTransportersNum") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->GetAllTransportersNum($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllClientsNum") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->GetAllClientsNum($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetAllCities") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['type'])) {
            $TOGOApp->GetAllCities($_POST['id'], $_POST['token'], $_POST['type']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getLocationUsers") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['cityId']) && isset($_POST['type'])) {
            $TOGOApp->getLocationUsers($_POST['id'], $_POST['token'], $_POST['cityId'], $_POST['type']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "transactionsByOrderForAdmin") {
        if (isset($_POST['customerId']) && isset($_POST['orderId']) && isset($_POST['adminId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->transactionsByOrderForAdmin($_POST['customerId'], $_POST['orderId'], $_POST['adminId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateTransporterPersonalInfo") {
        if (
            isset($_POST['id']) &&
            isset($_POST['token']) &&
            isset($_POST['info']) &&
            isset($_POST['personalImageCode']) &&
            isset($_POST['tempPersonalImageName']) &&
            isset($_POST['isPersonalImageUpdated']) &&
            isset($_POST['isPersonalNewImage']) &&
            isset($_POST['licenceImageCode']) &&
            isset($_POST['licenceImageName']) &&
            isset($_POST['isLicenceImageUpdated']) &&
            isset($_POST['isNewLicenceImage'])
        ) {
            $TOGOApp->updateTransporterPersonalInfo(
                $_POST['id'],
                $_POST['token'],
                $_POST['info'],
                $_POST['personalImageCode'],
                $_POST['tempPersonalImageName'],
                $_POST['isPersonalImageUpdated'],
                $_POST['isPersonalNewImage'],
                $_POST['licenceImageCode'],
                $_POST['licenceImageName'],
                $_POST['isLicenceImageUpdated'],
                $_POST['isNewLicenceImage']
            );
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateClientPersonalInfo") {
        if (
            isset($_POST['id']) &&
            isset($_POST['token']) &&
            isset($_POST['info']) &&
            isset($_POST['personalImageCode']) &&
            isset($_POST['tempPersonalImageName']) &&
            isset($_POST['isPersonalImageUpdated']) &&
            isset($_POST['isPersonalNewImage'])
        ) {
            $TOGOApp->updateClientPersonalInfo(
                $_POST['id'],
                $_POST['token'],
                $_POST['info'],
                $_POST['personalImageCode'],
                $_POST['tempPersonalImageName'],
                $_POST['isPersonalImageUpdated'],
                $_POST['isPersonalNewImage']
            );
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateClientBusinessInfo") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['info'])) {
            $TOGOApp->updateClientBusinessInfo($_POST['id'], $_POST['token'], $_POST['info']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllBalanceChargeActions") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getAllBalanceChargeActions($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllVerifyCodes") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getAllVerifyCodes($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getCustomerInfoForWayBill") { // edited (get customer info for admin view wey bill)
        if (isset($_POST['orderId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getCustomerInfoForWayBill($_POST['orderId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllNetworkMembers") {
        if (isset($_POST['transporterId']) && isset($_POST['deviceToken'])) {
            $TOGOApp->getAllNetworkMembers($_POST['transporterId'], $_POST['deviceToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "addNetworkMember") {
        if (isset($_POST['transporterId']) && isset($_POST['deviceToken']) && isset($_POST['mobileNumber'])) {
            $TOGOApp->addNetworkMember($_POST['transporterId'], $_POST['mobileNumber'], $_POST['deviceToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "activateNetwork") {
        if (isset($_POST['transporterId']) && isset($_POST['networkOwnerId']) && isset($_POST['deliveryCost']) && isset($_POST['verifyCode']) && isset($_POST['deviceToken']) && isset($_POST['description'])) {
            $TOGOApp->activateNetwork($_POST['transporterId'], $_POST['networkOwnerId'], $_POST['deliveryCost'], $_POST['verifyCode'], $_POST['deviceToken'], $_POST['description']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllNetworkInvitation") {
        if (isset($_POST['transporterId']) && isset($_POST['deviceToken'])) {
            $TOGOApp->getAllNetworkInvitation($_POST['transporterId'], $_POST['deviceToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "assignOrderToNetworkMember") {
        if (isset($_POST['orderId']) && isset($_POST['transporterId']) && isset($_POST['networkMemberId']) && isset($_POST['deviceToken'])) {
            $TOGOApp->assignOrderToNetworkMember($_POST['orderId'], $_POST['transporterId'], $_POST['networkMemberId'], $_POST['deviceToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "addTransporterToClientNetwork") {
        if (isset($_POST['clientId']) && isset($_POST['mobileNumber']) && isset($_POST['token'])) {
            $TOGOApp->addTransporterToClientNetwork($_POST['clientId'], $_POST['mobileNumber'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "CreateAssignedOrder") {
        if (isset($_POST['DeliveryParams']) && isset($_POST['AddressClint']) && isset($_POST['CustomerId']) && isset($_POST['TransporterId']) && isset($_POST['DeliveryCost']) && isset($_POST['TokenDevice'])) {
            $DeliveryParams = json_decode($_POST['DeliveryParams'], true);
            $AddressClint = json_decode($_POST['AddressClint'], true);
            $TOGOApp->CreateAssignedOrder($DeliveryParams, $AddressClint, $_POST['CustomerId'], $_POST['TransporterId'], $_POST['DeliveryCost'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "createAlbarqOrder") {
        if (isset($_POST['DeliveryParams']) && isset($_POST['AddressClint']) && isset($_POST['CustomerId']) && isset($_POST['TransporterId']) && isset($_POST['DeliveryCost']) && isset($_POST['TokenDevice'])) {
            $DeliveryParams = json_decode($_POST['DeliveryParams'], true);
            $AddressClint = json_decode($_POST['AddressClint'], true);
            $TOGOApp->createAlbarqOrder($DeliveryParams, $AddressClint, $_POST['CustomerId'], $_POST['TransporterId'], $_POST['DeliveryCost'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientNetwork") {
        if (isset($_POST['clientId']) && isset($_POST['token'])) {
            $TOGOApp->getClientNetwork($_POST['clientId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientNetworkForWeb") { // temp edit from the function apove for web (until it fixed for mobile)
        if (isset($_POST['clientId']) && isset($_POST['token'])/*  && isset($_POST['fromId']) && isset($_POST['toId']) */) {
            $TOGOApp->getClientNetworkForWeb($_POST['clientId'], $_POST['token']/* , $_POST['fromId'], $_POST['toId'] */);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterClientNetwork") {
        if (isset($_POST['transporterId']) && isset($_POST['token'])) {
            $TOGOApp->getTransporterClientNetwork($_POST['transporterId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "responseToAssignedOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['TransporterId']) && isset($_POST['Response']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->responseToAssignedOrder($_POST['OrderId'], $_POST['TransporterId'], $_POST['Response'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "ClientAssignOrder") {
        if (isset($_POST['CustomerId']) && isset($_POST['TransporterId']) && isset($_POST['OrderId']) && isset($_POST['DeliveryCost']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->ClientAssignOrder($_POST['CustomerId'], $_POST['TransporterId'], $_POST['OrderId'], $_POST['DeliveryCost'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "AcceptClientAssignOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['TransporterId']) && isset($_POST['IsAccept']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->AcceptClientAssignOrder($_POST['TransporterId'], $_POST['OrderId'], $_POST['IsAccept'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "MarkStuckOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['TransporterId']) && isset($_POST['StuckComment']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->MarkStuckOrder($_POST['TransporterId'], $_POST['OrderId'], $_POST['StuckComment'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "MarkReturnedOrder") {
        if (isset($_POST['OrderId']) && isset($_POST['TransporterId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->MarkReturnedOrder($_POST['TransporterId'], $_POST['OrderId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "finishReturnedOrder") {
        if (isset($_POST['transId']) && isset($_POST['tokenDevice']) && isset($_POST['orderId'])) {
            $TOGOApp->finishReturnedOrder($_POST['transId'], $_POST['tokenDevice'], $_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "AcceptReturnedOrder") { // it should be "ClientId" not "TransporterId" !!!
        if (isset($_POST['OrderId']) && isset($_POST['TransporterId']) && isset($_POST['IsAccepted']) &&  isset($_POST['TokenDevice'])) {
            $TOGOApp->AcceptReturnedOrder($_POST['TransporterId'], $_POST['OrderId'], $_POST['IsAccepted'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getFinancialTransactions") { /* edited (get transactions) */
        if (isset($_POST['customerId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->getFinancialTransactions($_POST['customerId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getFinancialTransactionsTest") { /* edited (get transactions) */
        if (isset($_POST['customerId'])) {
            $TOGOApp->getFinancialTransactionsTest($_POST['customerId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "requestWithdraw") {
        if (isset($_POST['customerId']) && isset($_POST['TokenDevice']) && isset($_POST['amount'])) {
            $TOGOApp->requestWithdraw($_POST['customerId'], $_POST['TokenDevice'], $_POST['amount']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getCustomerWithdrawRequests") {
        if (isset($_POST['customerId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->getCustomerWithdrawRequests($_POST['customerId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getCustomersWithdrawRequestsForAdmin") {
        if (isset($_POST['adminId']) && isset($_POST['adminToken'])) {
            $TOGOApp->getCustomersWithdrawRequestsForAdmin($_POST['adminId'], $_POST['adminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "completeWithdrawRequest") {
        if (isset($_POST['adminId']) && isset($_POST['adminToken']) && isset($_POST['withdrawalId']) && isset($_POST['ref'])) {
            $TOGOApp->completeWithdrawRequest($_POST['adminId'], $_POST['adminToken'], $_POST['withdrawalId'], $_POST['ref']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "rejectWithdrawRequest") {
        if (isset($_POST['adminId']) && isset($_POST['adminToken']) && isset($_POST['withdrawalId'])) {
            $TOGOApp->rejectWithdrawRequest($_POST['adminId'], $_POST['adminToken'], $_POST['withdrawalId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "transactionsByOrder") { /* edited (get transactions by order) */

        if (isset($_POST['customerId']) && isset($_POST['orderId']) && isset($_POST['TokenDevice'])) {

            $TOGOApp->transactionsByOrder($_POST['customerId'], $_POST['orderId'], $_POST['TokenDevice']);
        } else {

            echo "ParameterError";
        }
    } else if ($TypeFunction == "invoicesTest") { /* edited (get invoices) */
        if (isset($_POST['customerId'])) {
            $TOGOApp->invoicesTest($_POST['customerId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterInfo") { /* edited (getTransporterInfo) */
        if (isset($_POST['transporterId'])) {
            $TOGOApp->getTransporterInfo($_POST['transporterId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterPersonalInfo") { /* edited (getTransporterPersonalInfo) */
        if (isset($_POST['customerId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTransporterPersonalInfo($_POST['customerId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterWorkingTimes") { /* edited (getTransporterWorkingTimes) */
        if (isset($_POST['transporterId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTransporterWorkingTimes($_POST['transporterId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientPersonalInfo") { /* edited (getClientPersonalInfo) */
        if (isset($_POST['customerId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getClientPersonalInfo($_POST['customerId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientBusinessInfo") { /* edited (getClientBusinessInfo) */
        if (isset($_POST['customerId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getClientBusinessInfo($_POST['customerId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientTotalOrdersNum") { /* edited (getClientTotalOrdersNum) */
        if (isset($_POST['customerId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getClientTotalOrdersNum($_POST['customerId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterTotalOrdersNum") { /* edited (getTransporterTotalOrdersNum) */
        if (isset($_POST['customerId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTransporterTotalOrdersNum($_POST['customerId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTotalOrdersNum") { /* edited (getTotalOrdersNum) */
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['userType'])) {
            if ($_POST['userType'] == "client") {
                $TOGOApp->getTotalOrdersNumForClient($_POST['id'], $_POST['token']);
            } else {
                $TOGOApp->getTotalOrdersNumForTransporter($_POST['id'], $_POST['token']);
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterBusinessLocation") { /* edited (getTransporterBusinessLocation) */
        if (isset($_POST['transporterId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTransporterBusinessLocation($_POST['transporterId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateTransporterBusinessLocations") { /* edited (updateTransporterBusinessLocations) */
        if (isset($_POST['cityId']) && isset($_POST['checked']) && isset($_POST['transporterId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->updateTransporterBusinessLocations($_POST['cityId'], $_POST['checked'], $_POST['transporterId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "blockUser") { /* edited (updateTransporterBusinessLocations) */
        if (isset($_POST['customerId']) && isset($_POST['status']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->blockUser($_POST['customerId'], $_POST['status'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterOtherNetwork") { /* edited (getTransporterOtherNetwork) */
        if (isset($_POST['transporterId']) && isset($_POST['deviceToken'])) {
            $TOGOApp->getTransporterOtherNetwork($_POST['transporterId'], $_POST['deviceToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterDirectClients") { /* edited (getTransporterDirectClients) */
        if (isset($_POST['transporterId']) && isset($_POST['deviceToken'])) {
            $TOGOApp->getTransporterDirectClients($_POST['transporterId'], $_POST['deviceToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateClientAutoOffer") { /* edited (updateClientAutoOffer) */
        if (isset($_POST['transporterId']) && isset($_POST['tokenDevice']) && isset($_POST['status']) && isset($_POST['networkMemberId'])) {
            $TOGOApp->updateClientAutoOffer($_POST['transporterId'], $_POST['tokenDevice'], $_POST['status'], $_POST['networkMemberId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "acceptClientInvitation") { /* edited (acceptClientInvitation) */
        if (isset($_POST['transporterId']) && isset($_POST['tokenDevice']) && isset($_POST['networkMemberId'])) {
            $TOGOApp->acceptClientInvitation($_POST['transporterId'], $_POST['tokenDevice'], $_POST['networkMemberId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateTransporterAutoAccept") { /* edited (updateTransporterAutoAccept) */
        if (isset($_POST['transporterId']) && isset($_POST['tokenDevice']) && isset($_POST['status']) && isset($_POST['networkMemberId'])) {
            $TOGOApp->updateTransporterAutoAccept($_POST['transporterId'], $_POST['tokenDevice'], $_POST['status'], $_POST['networkMemberId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTimeLine") { /* edited (getTimeLine) */
        if (isset($_POST['orderId'])) {
            $TOGOApp->getTimeLine($_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTimeLineForAdmin") { /* edited (getTimeLine) */
        if (isset($_POST['orderId']) && isset($_POST['Adminid']) && isset($_POST['AdminToken'])) {
            $TOGOApp->getTimeLineForAdmin($_POST['orderId'], $_POST['Adminid'], $_POST['AdminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "recordAction") { /* edited (recordAction) */
        if (isset($_POST['party_one_id']) && isset($_POST['party_two_id']) && isset($_POST['order_id']) && isset($_POST['description']) && isset($_POST['action_id'])) {
            $TOGOApp->recordAction($_POST['party_one_id'], $_POST['party_two_id'], $_POST['order_id'], $_POST['description'], $_POST['action_id']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getRecordsActions") { /* edited (getRecordsActions) */
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getRecordsActions($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getLastRecord") { /* edited (getLastRecord) */
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getLastRecord($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getOrderActions") { /* edited (getOrderActions) */
        if (isset($_POST['order_id']) && isset($_POST['customerId']) && isset($_POST['tokenDevice'])) {
            $TOGOApp->getOrderActions($_POST['order_id'], $_POST['customerId'], $_POST['tokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getOrderActionsForAdmin") { /* edited (getOrderActionsForAdmin) */
        if (isset($_POST['order_id']) && isset($_POST['Adminid']) && isset($_POST['AdminToken'])) {
            $TOGOApp->getOrderActionsForAdmin($_POST['order_id'], $_POST['Adminid'], $_POST['AdminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "cancelAssignedOrder") { /* edited (cancelAssignedOrder) */
        if (isset($_POST['orderId']) && isset($_POST['transporterId']) && isset($_POST['tokenDevice'])) {
            $TOGOApp->cancelAssignedOrder($_POST['orderId'], $_POST['transporterId'], $_POST['tokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTotalBalance") { /* edited (getTotalBalance) */
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTotalBalance($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getLastOrderId") { /* edited (getLastOrderId) */
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getLastOrderId($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "GetTransporterVehiclesInfo") { /* edited (GetTransporterVehiclesInfo) */
        if (isset($_POST['transporter_id']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->GetTransporterVehiclesInfo($_POST['transporter_id'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "tempRegisterAdmin") { /* edited (tempRegisterAdmin) */
        $TOGOApp->tempRegisterAdmin();
    } else if ($TypeFunction == "adminCheckToLoginLogin") {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $TOGOApp->adminCheckToLoginLogin($_POST['username'], $_POST['password']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "adminLogin") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['code'])) {
            $TOGOApp->adminLogin($_POST['id'], $_POST['token'], $_POST['code']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "sendAdminLoginVerificationCode") {
        if (isset($_POST['adminId'])) {
            $TOGOApp->sendAdminLoginVerificationCode($_POST['adminId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "testNot") { /* test firebase push-notifications system */
        if (isset($_POST['token'])) {
            $TOGOApp->testNot($_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateWebNotificationToken") {
        if (isset($_POST['userId']) && isset($_POST['tokenDevice']) && isset($_POST['newWebToken'])) {
            $TOGOApp->updateWebNotificationToken($_POST['userId'], $_POST['tokenDevice'], $_POST['newWebToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateAdminWebNotificationToken") {
        if (isset($_POST['Adminid']) && isset($_POST['AdminToken']) && isset($_POST['newWebToken'])) {
            $TOGOApp->updateAdminWebNotificationToken($_POST['Adminid'], $_POST['AdminToken'], $_POST['newWebToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "sendVerificationCode") {
        if (isset($_POST['phoneNumber'])) {
            $TOGOApp->sendVerificationCode($_POST['phoneNumber']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "sendVerificationCodeForNewUser") {
        if (isset($_POST['phoneNumber'])) {
            $TOGOApp->sendVerificationCodeForNewUser($_POST['phoneNumber']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "loginWithNumber") {
        if (isset($_POST['customerId']) && isset($_POST['code'])) {
            $TOGOApp->loginWithNumber($_POST['customerId'], $_POST['code']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "registerClientByPhoneNumber") {
        if (isset($_POST['infoArr']) && isset($_POST['imageName']) && isset($_POST['imageCode']) && isset($_POST['verifiCode'])) {
            $TOGOApp->registerClientByPhoneNumber($_POST['infoArr'], $_POST['imageName'], $_POST['imageCode'], $_POST['verifiCode']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "registerTransporterByPhoneNumber") {
        if (isset($_POST['infoArr']) && isset($_POST['imageName']) && isset($_POST['imageCode']) && isset($_POST['verifiCode'])) {
            $TOGOApp->registerTransporterByPhoneNumber($_POST['infoArr'], $_POST['imageName'], $_POST['imageCode'], $_POST['verifiCode']);
        } else {
            echo "ParameterError";
        }
    }/*  else if ($TypeFunction == "tempAddCity") {
        if (isset($_POST['id']) && isset($_POST['name'])) {
            $TOGOApp->tempAddCity($_POST['id'], $_POST['name']);
        } else {
            echo "ParameterError";
        }
    } */ else if ($TypeFunction == "GetCitiesArea") {
        if (isset($_POST['clientId']) && isset($_POST['tokenDevice']) && isset($_POST['type']) && isset($_POST['superId']) && isset($_POST['langId'])) {
            $TOGOApp->GetCitiesArea($_POST['clientId'], $_POST['type'], $_POST['superId'], $_POST['langId'], $_POST['tokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterCitiesPrices") {
        if (isset($_POST['transporterId']) && isset($_POST['tokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getTransporterCitiesPrices($_POST['transporterId'], $_POST['tokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterCitiesPricesForAdmin") {
        if (isset($_POST['transId']) && isset($_POST['langId']) && isset($_POST['adminId']) && isset($_POST['adminToken'])) {
            $TOGOApp->getTransporterCitiesPricesForAdmin($_POST['transId'], $_POST['langId'], $_POST['adminId'], $_POST['adminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllGovernorates") {
        if (isset($_POST['transporterId']) && isset($_POST['tokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getAllGovernorates($_POST['transporterId'], $_POST['tokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateTransporterCitiesPrices") {
        if (isset($_POST['transporterId']) && isset($_POST['tokenDevice']) && isset($_POST['fromId']) && isset($_POST['toId']) && isset($_POST['newPrice'])) {
            $TOGOApp->updateTransporterCitiesPrices($_POST['transporterId'], $_POST['tokenDevice'], $_POST['fromId'], $_POST['toId'], $_POST['newPrice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateTransporterCitiesPricesForAdmin") {
        if (isset($_POST['transId']) && isset($_POST['fromId']) && isset($_POST['toId']) && isset($_POST['newPrice']) && isset($_POST['adminId']) && isset($_POST['adminToken'])) {
            $TOGOApp->updateTransporterCitiesPricesForAdmin($_POST['transId'], $_POST['fromId'], $_POST['toId'], $_POST['newPrice'], $_POST['adminId'], $_POST['adminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientDefaultAddress") {
        if (isset($_POST['clientId']) && isset($_POST['tokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getClientDefaultAddress($_POST['clientId'], $_POST['tokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getLogestechsExclusiveClientDefaultAddress") {
        if (isset($_POST['clientId']) && isset($_POST['tokenDevice']) && isset($_POST['langId'])) {
            $TOGOApp->getLogestechsExclusiveClientDefaultAddress($_POST['clientId'], $_POST['tokenDevice'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getClientTempAddress") {
        if (isset($_POST['clientId']) && isset($_POST['tokenDevice']) && isset($_POST['langId']) && isset($_POST['addressId'])) {
            $TOGOApp->getClientTempAddress($_POST['clientId'], $_POST['tokenDevice'], $_POST['langId'], $_POST['addressId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "setClientDefaultAddress") {
        if (isset($_POST['clientId']) && isset($_POST['tokenDevice']) && isset($_POST['addressId'])) {
            $TOGOApp->setClientDefaultAddress($_POST['clientId'], $_POST['tokenDevice'], $_POST['addressId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "setLogestechsExclusiveClientDefaultAddress") {
        if (isset($_POST['clientId']) && isset($_POST['tokenDevice']) && isset($_POST['addressId'])) {
            $TOGOApp->setLogestechsExclusiveClientDefaultAddress($_POST['clientId'], $_POST['tokenDevice'], $_POST['addressId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "deleteOrderBeforePickupForAdmin") {
        if (isset($_POST['orderId']) && isset($_POST['adminId']) && isset($_POST['adminToken'])) {
            $TOGOApp->deleteOrderBeforePickupForAdmin($_POST['orderId'], $_POST['adminId'], $_POST['adminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "deleteNewOrderForAdmin") {
        if (isset($_POST['orderId']) && isset($_POST['adminId']) && isset($_POST['adminToken'])) {
            $TOGOApp->deleteNewOrderForAdmin($_POST['orderId'], $_POST['adminId'], $_POST['adminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "isUserLogedIn") {
        if (isset($_POST['useId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->isUserLogedIn($_POST['useId'], $_POST['TokenDevice']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "tempUpdateNewTransporterPrices") {
        if (true) {
            $TOGOApp->tempUpdateNewTransporterPrices();
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "isAdminLogedIn") {
        if (isset($_POST['adminId']) && isset($_POST['AdminToken'])) {
            $TOGOApp->isAdminLogedIn($_POST['adminId'], $_POST['AdminToken']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "testFunction") {
        $TOGOApp->testFunction();
    } else if ($TypeFunction == "testApi") {
        $TOGOApp->testApi();
    } else if ($TypeFunction == "testOddoInvoice") {
        $TOGOApp->testOddoInvoice();
    } else if ($TypeFunction == "recordRechargeBalance") {
        $TOGOApp->recordRechargeBalance($_POST['amount'], $_POST['mobile']);
    } else if ($TypeFunction == "tempAddFunction") {
        $TOGOApp->tempAddFunction();
    } else if ($TypeFunction == "getLogisticsVillagesTest") { /* test firebase push-notifications system */
        if (isset($_POST['str'])) {
            $TOGOApp->getLogisticsVillagesTest($_POST['str']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllCitiesTest") { /* test firebase push-notifications system */
        if (isset($_POST['lang'])) {
            $TOGOApp->getAllCitiesTest($_POST['lang']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getLogestechsArea") { /* test firebase push-notifications system */
        if (isset($_POST['searchStr'])) {
            $TOGOApp->getLogestechsArea($_POST['searchStr']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "sendCustomNotification") { /* test firebase push-notifications system */
        if (isset($_POST['customerId']) && isset($_POST['token']) && isset($_POST['filter']) && isset($_POST['orderId'])) {
            $TOGOApp->sendCustomNotification($_POST['customerId'], $_POST['token'], $_POST['filter'], $_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "addBarqLogs") { /* test firebase push-notifications system */

        $TOGOApp->addBarqLogs($_POST['searchStr']);
    } else if ($TypeFunction == "test1234") {

        $TOGOApp->test1234();
    } else if ($TypeFunction == "getAllTransportersToAdd") {
        if (isset($_POST['customerId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->getAllTransportersToAdd($_POST['customerId'], $_POST['TokenDevice']);
        }
    } else if ($TypeFunction == "getLogestechsAllAreas") {

        $TOGOApp->getLogestechsAllAreas();
    } else if ($TypeFunction == "getLogestechsPrice") {

        $TOGOApp->getLogestechsPrice($_POST['companyId'], $_POST['sourceReg'], $_POST['destReg']);
    } else if ($TypeFunction == "getAllAreas") {

        $TOGOApp->getAllAreas();
    } else if ($TypeFunction == "addToLogesAreas") {
        if (isset($_POST['togoAreaId']) && isset($_POST['togoAreaName']) && isset($_POST['logesVillageName'])) {
            $TOGOApp->addToLogesAreas($_POST['togoAreaId'], $_POST['togoAreaName'], $_POST['logesVillageName']);
        }
    } else if ($TypeFunction == "updateTogoAreaName") {
        if (isset($_POST['areaId']) && isset($_POST['newArName']) && isset($_POST['newEnName'])) {
            $TOGOApp->updateTogoAreaName($_POST['areaId'], $_POST['newArName'], $_POST['newEnName']);
        }
    } else if ($TypeFunction == "tempMarkArea") {
        if (isset($_POST['id']) && isset($_POST['areaName']) && isset($_POST['cityName'])) {
            $TOGOApp->tempMarkArea($_POST['id'], $_POST['areaName'], $_POST['cityName']);
        }
    } else if ($TypeFunction == "checkLogestechsPrice") {
        if (isset($_POST['companyId']) && isset($_POST['areaId1']) && isset($_POST['areaId2'])) {
            $TOGOApp->checkLogestechsPrice($_POST['companyId'], $_POST['areaId1'], $_POST['areaId2']);
        }
    } else if ($TypeFunction == "checkLogestechsPriceTest") {
        if (true) {
            $TOGOApp->checkLogestechsPriceTest();
        }
    } else if ($TypeFunction == "createLogestechsOrder") {

        $TOGOApp->createLogestechsOrder();
    } else if ($TypeFunction == "fetchSupersetGusetToken") {

        $TOGOApp->fetchSupersetGusetToken();
    } else if ($TypeFunction == "nonoTest") {
        if (isset($_POST['isClient'])) {
            $TOGOApp->nonoTest($_POST['isClient']);
        }
    } else if ($TypeFunction == "getClientNetworkForAdmin") {
        if (isset($_POST['adminId']) && isset($_POST['clientId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->getClientNetworkForAdmin($_POST['adminId'], $_POST['TokenDevice'], $_POST['clientId']);
        }
    } else if ($TypeFunction == "getClientPriceList") {
        if (isset($_POST['adminId']) && isset($_POST['clientId']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->getClientPriceList($_POST['adminId'], $_POST['TokenDevice'], $_POST['clientId']);
        }
    } else if ($TypeFunction == "updateClientDeliveryCostList") {
        if (isset($_POST['adminId']) && isset($_POST['clientId']) && isset($_POST['cost']) && isset($_POST['areas']) && isset($_POST['TokenDevice'])) {
            $TOGOApp->updateClientDeliveryCostList($_POST['adminId'], $_POST['TokenDevice'], $_POST['clientId'], $_POST['cost'], $_POST['areas']);
        }
    } else if ($TypeFunction == "updateClientAutoOfferForAdmin") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['status']) && isset($_POST['networkMemberId'])) {
            $TOGOApp->updateClientAutoOfferForAdmin($_POST['id'], $_POST['token'], $_POST['status'], $_POST['networkMemberId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getAllTransportersToAddForAdmin") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['clientId'])) {
            $TOGOApp->getAllTransportersToAddForAdmin($_POST['id'], $_POST['token'], $_POST['clientId']);
        }
    } else if ($TypeFunction == "AddTransporterToClientNetworkFoAdmin") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['clientId']) && isset($_POST['mobileNumber'])) {
            $TOGOApp->AddTransporterToClientNetworkFoAdmin($_POST['id'], $_POST['token'], $_POST['clientId'], $_POST['mobileNumber']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTotalWalletsBalance") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTotalWalletsBalance($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getOliveryPrice") {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['sourceName']) && isset($_POST['destinationName'])) {
            $TOGOApp->getOliveryPrice($_POST['username'], $_POST['password'], $_POST['sourceName'], $_POST['destinationName']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateOrderReviewedStatus") {
        if (isset($_POST['status']) && isset($_POST['orderId'])) {
            $TOGOApp->updateOrderReviewedStatus($_POST['status'], $_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "updateReviewedOrders") {
        if (isset($_POST['orderIds']) && isset($_POST['isToReview'])) {
            $TOGOApp->updateReviewedOrders($_POST['orderIds'], $_POST['isToReview'], $_POST['isTransporter']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getOrdersToExport") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['userTpye']) && isset($_POST['filterStr']) && isset($_POST['langId'])) {
            if ($_POST['userTpye'] == "client") {
                $TOGOApp->getClientOrdersToExport($_POST['id'], $_POST['token'], $_POST['filterStr'], $_POST['langId']);
            } else if ($_POST['userTpye'] == "transporter") {
                $TOGOApp->getTransporterOrdersToExport($_POST['id'], $_POST['token'], $_POST['filterStr'], $_POST['langId']);
            } else {
                echo "user type error";
            }
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getTransporterTransactionsToExport") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTransporterTransactionsToExport($_POST['id'], $_POST['token']);
        }
    } else if ($TypeFunction == "fetchSupersetGusetToken_2") {
        $TOGOApp->fetchSupersetGusetToken_2();
    } else if ($TypeFunction == "getTempBalance") {
        $TOGOApp->getTempBalance();
    } else if ($TypeFunction == "getPotato") {
        $TOGOApp->getPotato($_POST['test']);
    } else if ($TypeFunction == "getPotato1") {
        $TOGOApp->getPotato($_POST['potato']);
    } else if ($TypeFunction == "testOlive") {
        $TOGOApp->createOliveryOrder();
    } else if ($TypeFunction == "getClientInfo") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getClientInfo($_POST['id'], $_POST['token']);
        }
    } else if ($TypeFunction == "getClientActiveOrdersCount") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getClientActiveOrdersCount($_POST['id'], $_POST['token']);
        }
    } else if ($TypeFunction == "getClientFinishedOrdersCount") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getClientFinishedOrdersCount($_POST['id'], $_POST['token']);
        }
    } else if ($TypeFunction == "TestGet41ClientNewOrders") {
        $TOGOApp->TestGet41ClientNewOrders();
    } else if ($TypeFunction == "getTotalTempBalance") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getTotalTempBalance($_POST['id'], $_POST['token']);
        }
    } else if ($TypeFunction == "getUserTotalTempBalance") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['userId'])) {
            $TOGOApp->getUserTotalTempBalance($_POST['id'], $_POST['token'], $_POST['userId']);
        }
    } else if ($TypeFunction == "getErrMsg") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['orderId'])) {
            $TOGOApp->getErrMsg($_POST['id'], $_POST['token'], $_POST['orderId']);
        }
    } else if ($TypeFunction == "lendMoney") {
        if (isset($_POST['adminId']) && isset($_POST['token']) && isset($_POST['userId']) && isset($_POST['amount']) && isset($_POST['code'])) {
            $TOGOApp->lendMoney($_POST['adminId'], $_POST['token'], $_POST['userId'], $_POST['amount'], $_POST['code']);
        }
    } else if ($TypeFunction == "collectMoney") {
        if (isset($_POST['adminId']) && isset($_POST['token']) && isset($_POST['userId']) && isset($_POST['amount']) && isset($_POST['code'])) {
            $TOGOApp->collectMoney($_POST['adminId'], $_POST['token'], $_POST['userId'], $_POST['amount'], $_POST['code']);
        }
    } else if ($TypeFunction == "getTempTransactions") {
        if (isset($_POST['adminId']) && isset($_POST['token']) && isset($_POST['userId'])) {
            $TOGOApp->getTempTransactions($_POST['adminId'], $_POST['token'], $_POST['userId']);
        }
    } else if ($TypeFunction == "sendLoanVerifyCodeForAdmin") {
        if (isset($_POST['adminId']) && isset($_POST['token']) && isset($_POST['fullName']) && isset($_POST['amount']) && isset($_POST['actionType'])) {
            $TOGOApp->sendLoanVerifyCodeForAdmin($_POST['adminId'], $_POST['token'], $_POST['fullName'], $_POST['amount'], $_POST['actionType']);
        }
    } else if ($TypeFunction == "getBalanceTest") {
        if (isset($_POST['id'])) {
            $TOGOApp->getBalanceTest($_POST['id']);
        }
    } else if ($TypeFunction == "getOrdersCount") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getOrdersCount($_POST['id'], $_POST['token']);
        }
    } else if ($TypeFunction == "AdminRemoveAddErrorMark") {
        if (isset($_POST['orderId']) && isset($_POST['status']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->AdminRemoveAddErrorMark($_POST['orderId'], $_POST['status'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "undoCancledActiveOrder") {
        if (isset($_POST['orderId']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->undoCancledActiveOrder($_POST['orderId'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "alterActiveOrderCOD") {
        if (isset($_POST['orderId']) && isset($_POST['newCOD']) && isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->alterActiveOrderCOD($_POST['orderId'], $_POST['newCOD'], $_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "NISToJOD") {
        if (isset($_POST['amount'])) {
            $TOGOApp->NISToJOD($_POST['amount']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_SendVerificationCode") {
        if (isset($_POST['phoneNumber'])) {
            $TOGOApp->food_SendVerificationCode($_POST['phoneNumber']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getCitiesAndAreas") {
        if (isset($_POST['langId'])) {
            $TOGOApp->getCitiesAndAreas($_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_registerUser") {
        if (isset($_POST['userInfo']) && isset($_POST['verificationCode'])) {
            $TOGOApp->food_registerUser($_POST['userInfo'], $_POST['verificationCode']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getClientMainPersonalInfo") {
        if (isset($_POST['customerId']) && isset($_POST['token'])) {
            $TOGOApp->food_getClientMainPersonalInfo($_POST['customerId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getCustomers") {
        if (isset($_POST['customerId']) && isset($_POST['token']) && isset($_POST['langId'])) {
            $TOGOApp->food_getCustomers($_POST['customerId'], $_POST['token'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getCustomersLocations") {
        if (isset($_POST['customerId']) && isset($_POST['token'])) {
            $TOGOApp->food_getCustomersLocations($_POST['customerId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_addCustomer") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['customerPhoneNumber']) && isset($_POST['areaId'])) {
            $TOGOApp->food_addCustomer($_POST['clientCustomerId'], $_POST['token'], $_POST['customerPhoneNumber'], $_POST['areaId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getClientAreas") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['langId'])) {
            $TOGOApp->food_getClientAreas($_POST['clientCustomerId'], $_POST['token'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_createFoodOrder") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['deliveryParams']) && isset($_POST['addresses'])) {
            $TOGOApp->food_createFoodOrder($_POST['clientCustomerId'], $_POST['token'], $_POST['deliveryParams'], $_POST['addresses']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getClientOrderDetails") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['orderId']) && isset($_POST['langId'])) {
            $TOGOApp->food_getClientOrderDetails($_POST['clientCustomerId'], $_POST['token'], $_POST['orderId'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_clientCancelNewOrder") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['orderId'])) {
            $TOGOApp->food_clientCancelNewOrder($_POST['clientCustomerId'], $_POST['token'], $_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getAllNewOrders") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['langId'])) {
            $TOGOApp->food_getAllNewOrders($_POST['clientCustomerId'], $_POST['token'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getAllActiveOrders") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['langId'])) {
            $TOGOApp->food_getAllActiveOrders($_POST['clientCustomerId'], $_POST['token'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getAllFinishedOrders") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token']) && isset($_POST['langId'])) {
            $TOGOApp->food_getAllFinishedOrders($_POST['clientCustomerId'], $_POST['token'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getAllNewOrdersCount") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token'])) {
            $TOGOApp->food_getAllNewOrdersCount($_POST['clientCustomerId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getAllActiveOrdersCount") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token'])) {
            $TOGOApp->food_getAllActiveOrdersCount($_POST['clientCustomerId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getAllFinishedOrdersCount") {
        if (isset($_POST['clientCustomerId']) && isset($_POST['token'])) {
            $TOGOApp->food_getAllFinishedOrdersCount($_POST['clientCustomerId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_updateAvailability") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['status'])) {
            $TOGOApp->food_updateAvailability($_POST['transporterCustomerId'], $_POST['token'], $_POST['status']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_updateTransporterLocation") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['long']) && isset($_POST['lat'])) {
            $TOGOApp->food_updateTransporterLocation($_POST['transporterCustomerId'], $_POST['token'], $_POST['long'], $_POST['lat']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getTransporterActiveOrders") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['langId'])) {
            $TOGOApp->food_getTransporterActiveOrders($_POST['transporterCustomerId'], $_POST['token'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getTransporterFinsihedOrders") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['langId'])) {
            $TOGOApp->food_getTransporterFinsihedOrders($_POST['transporterCustomerId'], $_POST['token'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_getTransporterTransactions") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token'])) {
            $TOGOApp->food_getTransporterTransactions($_POST['transporterCustomerId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_responseToNewOrder") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['orderId']) && isset($_POST['response'])) {
            $TOGOApp->food_responseToNewOrder($_POST['transporterCustomerId'], $_POST['token'], $_POST['orderId'], $_POST['response']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_pickupOrder") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['orderId'])) {
            $TOGOApp->food_pickupOrder($_POST['transporterCustomerId'], $_POST['token'], $_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_finishOrder") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['orderId'])) {
            $TOGOApp->food_finishOrder($_POST['transporterCustomerId'], $_POST['token'], $_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "food_confirmFinishOrder") {
        if (isset($_POST['transporterCustomerId']) && isset($_POST['token']) && isset($_POST['orderId'])/*  && isset($_POST['verificationCode']) */) {
            $TOGOApp->food_confirmFinishOrder($_POST['transporterCustomerId'], $_POST['token'], $_POST['orderId']/* , $_POST['verificationCode'] */);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "customerEditCOD") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['orderId']) && isset($_POST['newCod']) && isset($_POST['newCurrency'])) {
            $TOGOApp->customerEditCOD($_POST['id'], $_POST['token'], $_POST['orderId'], $_POST['newCod'], $_POST['newCurrency']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "customerEditOrderNotes") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['orderId']) && isset($_POST['notes'])) {
            $TOGOApp->customerEditOrderNotes($_POST['id'], $_POST['token'], $_POST['orderId'], $_POST['notes']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getOrderInfoForReturnedOrder") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['orderId']) && isset($_POST['langId'])) {
            $TOGOApp->getOrderInfoForReturnedOrder($_POST['id'], $_POST['token'], $_POST['orderId'], $_POST['langId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "testSMSNono") {
        if (true) {
            $TOGOApp->testSMSNono();
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "create_in_invoice_test") {
        echo "api deactivated";
        return;
        // $transporter_id, $order_id, $foreign_order_barcode, $amount
        if (isset($_POST['transporter_id']) && isset($_POST['order_id']) && isset($_POST['foreign_order_barcode']) && isset($_POST['amount'])) {
            $TOGOApp->create_in_invoice($_POST['transporter_id'], $_POST['order_id'], $_POST['foreign_order_barcode'], $_POST['amount']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "sendFCMNotificationTest") {
        /* echo "api deactivated";
        return; */
        if (isset($_POST['token'])) {
            $TOGOApp->sendFCMNotificationTest($_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getBalanceTestttttt") {
        if (isset($_POST['key']) && isset($_POST['customerId'])) {
            $TOGOApp->getBalanceTestttttt($_POST['key'], $_POST['customerId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "addExclusiveCustomer") {
        if (isset($_POST['transporterId']) && isset($_POST['token']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])) {
            $TOGOApp->addExclusiveCustomer($_POST['transporterId'], $_POST['token'], $_POST['name'], $_POST['email'], $_POST['phone']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveCustomers") {
        if (isset($_POST['transporterId']) && isset($_POST['token'])) {
            $TOGOApp->getExclusiveCustomers($_POST['transporterId'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getLogestechsAreaByName") {
        if (isset($_POST['customerId']) && isset($_POST['searchStr'])) {
            $TOGOApp->getLogestechsAreaByName($_POST['customerId'], $_POST['searchStr']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getALlLogestechsAreas") {
        if (isset($_POST['customerId'])) {
            $TOGOApp->getALlLogestechsAreas($_POST['customerId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getNotifications") {
        if (isset($_POST['id']) && isset($_POST['token'])) {
            $TOGOApp->getNotifications($_POST['id'], $_POST['token']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getOrderNotification") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['orderId'])) {
            $TOGOApp->getOrderNotification($_POST['id'], $_POST['token'], $_POST['orderId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "unmarkNotification") {
        if (isset($_POST['id']) && isset($_POST['token']) && isset($_POST['notificationId'])) {
            $TOGOApp->unmarkNotification($_POST['id'], $_POST['token'], $_POST['notificationId']);
        } else {
            echo "ParameterError";
        }
    } else if ($TypeFunction == "getExclusiveLogestechsClientAddressesTest") {
        $TOGOApp->getExclusiveLogestechsClientAddresses(292, "", 2);
    } else if ($TypeFunction == "aaaaaakhkhkhkhkh") {
        $TOGOApp->aaaaaakhkhkhkhkh();
    } else
        echo "Function Not Found";
} else {
    echo "No Parameters";
}
