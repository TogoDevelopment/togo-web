<?php


class ClientService
{
    private $dataBase;

    /**
     * ClientService constructor.
     * @param $dataBase
     */
    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getClient($clientId)
    {
        $client = array();
        $query = "select customer.PhoneNumber, clienttable.Email, concat(clienttable.FirstName, ' ', clienttable.LastName) as fullname from togo.customer as customer
        inner join togo.clienttable as clienttable on customer.id = clienttable.CustomerId
        where customer.id='$clientId'";

        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($client, $row);
        }

        return $client;
    }

    public function getClientAddresses($clientId)
    {
        $addresses = array();
        $query = "select * from addresses where customer_id=$clientId";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($addresses, $row);
        }

        return $addresses;
    }

    public function getClientDefaultAddress($clientId, $langId)
    {
        $query = "select defaultAddress.*, area.name as areaName, citylang.name as cityName, governoratelang.name as govName, provincelang.name as provName
        from togo.addresses as defaultAddress 
        inner join togo.arealang as area on defaultAddress.areaId=area.areaId 
        inner join togo.citylang as citylang on defaultAddress.cityId = citylang.cityId 
        inner join togo.governoratelang as governoratelang on defaultAddress.governoratId = governoratelang.governorateId
        inner join togo.provincelang as provincelang on defaultAddress.provinceId = provincelang.provinceId
        where customer_id='$clientId' and is_default=1 
        and area.languageId='$langId'
        and citylang.languageId='$langId'
        and governoratelang.languageId='$langId'
        and provincelang.languageId='$langId'";

        $result = $this->dataBase->query($query);
        $row = $this->dataBase->fetchArray($result);

        return $row;
    }

    public function getClientTempAddress($langId, $addressId)
    {
        $query = "select addresses.*, area.name as areaName from togo.addresses as addresses inner join togo.arealang as area on addresses.areaId=area.areaId where addresses.id='$addressId' and area.languageId='$langId'";
        $result = $this->dataBase->query($query);
        $row = $this->dataBase->fetchArray($result);

        return $row;
    }

    public function setClientDefaultAddress($clientId, $addressId)
    {

        /* echo $clientId . " -- " . $addressId;
        return; */

        $addressId = intval($addressId);

        $query_clearPreviousDefault = "update togo.addresses set is_default=0 where customer_id='$clientId'";
        $result_clearPreviousDefault = $this->dataBase->query($query_clearPreviousDefault);

        if ($result_clearPreviousDefault) {
            $query_setNewDefault = "update togo.addresses set is_default=1 where id='$addressId'";
            $result_setNewDefault = $this->dataBase->query($query_setNewDefault);

            if ($result_setNewDefault) {
                echo "updated";
            } else {
                echo "newDefaultUpdateError!";
            }
        } else {
            echo "previousDefaultUpdateError!";
        }
    }

    // edited, new function, get client addresses (only the addresses created by this client without shared addresses created by other customers) to choose default address from
    public function getPrivateAddresses($clientId, $searchText, $langId)
    {
        $addresses = array();
        $query = "select addresses.*, citylang.name as cityName from togo.addresses as addresses inner join togo.citylang as citylang on addresses.cityId = citylang.cityId where (addresses.name LIKE '%$searchText%' or phone_number LIKE '%$searchText%') and (creator_id='$clientId') and citylang.languageId='$langId'";
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($addresses, $row);
        }

        return $addresses;
    }

    public function getAddresses($clientId, $searchText, $langId)
    {
        $addresses = array();
        //        $query = "select cityregionlang.CityName, orderbidaddress.ReceiverAddressNum, orderbidaddress.contact_name,
        //                orderbidaddress.NameStreet, orderbidaddress.NameBuilding, orderbidaddress.FloorNumbers
        //                From orderbidaddress
        //                Inner join cityregionlang on orderbidaddress.IdCity = cityregionlang.CityId
        //                Inner join orderbidengin on orderbidaddress.IdOrderBidEngin = orderbidengin.id
        //                Where (orderbidengin.CustomerId = '$clientId' or orderbidaddress.shared_contact = b'1')
        //                And (orderbidaddress.contact_name LIKE '%$searchText%' or orderbidaddress.ReceiverAddressNum LIKE '%$searchText%')
        //                And cityregionlang.languageId = '$langId'";

        // $query = "select * from addresses where (name LIKE '%$searchText%' or phone_number LIKE '%$searchText%')  and (creator_id= $clientId or is_shared = 1)";
        $query = "select addresses.*, citylang.name as cityName, arealang.name as areaName, governoratelang.name as govName, provincelang.name as provName
                from togo.addresses as addresses
                inner join togo.citylang as citylang on addresses.cityId = citylang.cityId 
                inner join togo.arealang as arealang on addresses.areaId = arealang.areaId
                inner join togo.governoratelang as governoratelang on addresses.governoratId = governoratelang.governorateId
                inner join togo.provincelang as provincelang on addresses.provinceId = provincelang.provinceId
                where (addresses.name LIKE '%$searchText%' or phone_number LIKE '%$searchText%') 
                and (creator_id='$clientId') 
                and citylang.languageId='$langId'
                and arealang.languageId='$langId'
                and governoratelang.languageId='$langId'
                and provincelang.languageId='$langId' order by addresses.id desc";

                // or (is_shared = 1 and is_default = 0)
                
        $result = $this->dataBase->query($query);
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($addresses, $row);
        }

        return $addresses;
    }
}
