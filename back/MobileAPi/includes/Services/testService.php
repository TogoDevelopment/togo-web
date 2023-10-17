<?php

require_once(str_replace('//', '/', dirname(__FILE__) . '/') . '../Helpers.php');

class TOGO_Test
{

    private $dataBase;

    /**
     * PickupService constructor.
     * @param $dataBase
     */
    public function __construct()
    {        
        $this->connectDatabase();
    }

    public function connectDatabase()
    {
        require_once(str_replace('//', '/', dirname(__FILE__) . '/') . '../database.php');
        $this->dataBase = $database;
    }

    public function testFunc()
    {
        $query = "SELECT PhoneNumber FROM togo.customer WHERE id = 41";
        $result = $this->dataBase->query($query);
        $row = $this->dataBase->fetchArray($result);

        $phoneNumber = $row['PhoneNumber'];

        echo $phoneNumber;
    }
}
