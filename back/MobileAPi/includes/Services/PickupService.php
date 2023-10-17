<?php


class PickupService
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


    public function checkQrCode($qrCode, $transporterId, $orderId)
    {

        $query = "Select * from togo.orderbidengin where " . ($orderId == "" ? "qr_code='$qrCode'" : "id='$orderId'") . " and (DeliveryId = '$transporterId' or TeamMemberId = '$transporterId') and isnull(pickup_date)";
        $result = $this->dataBase->query($query);
        $row = $this->dataBase->fetchArray($result);

        if (sizeof($row) > 0) {
            if ($row['qr_code'] == "") {
                $query = "update togo.orderbidengin set qr_code='$qrCode' where id='$orderId'";
                $this->dataBase->query($query);
            } else {
                if ($qrCode != $row['qr_code'])
                    return null;
            }
            return $row['id'];
        }

        return null;
    }
}