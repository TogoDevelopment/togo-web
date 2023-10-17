<?php

/*
  defining database functions
 */


class DB
{

    private $connect;

    function __construct()
    {

        $this->connect = $this->openConnection();
    }

    public function openConnection()
    {

//        $conn = new mysqli("localhost:3306", "root", "root", "togo") or die("no connect");
        // $conn = new mysqli("localhost:3306", "root", "2go.2018@adm9n", "togo") or die("no connect");
        $conn = new mysqli("192.168.14.35:3306", "root", "adm9n@Z0ne", "togo") or die("no connect");
        mysqli_set_charset($conn, "utf8");
        $this->connect = $conn;
        if (!$conn) {
            echo "Connection could not be established.\n";
            //die( print_r( sqlsrv_errors(), true));
        }

        //echo "connection start";

        return $this->connect;
    }

    public function getConnect()
    {

        return $this->connect;

    }

    public function query($query)
    {

        $result = mysqli_query($this->getConnect(), $query);

        return $result;
    }

    public function getLastIdInserted()
    {
        return mysqli_insert_id($this->getConnect());
    }


    public function escape($data)
    {
        if (!isset($data) or empty($data)) return '';
        if (is_numeric($data)) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/',            // 14-31
            '[\000\010\011\012\015\032\042\047\134\140]'
        );
        foreach ($non_displayables as $regex) {
            $data = preg_replace($regex, '', $data);
            $escaped_string = str_replace("'", "''", $data);
        }

        return $escaped_string;
    }

 /*    public function real_escape($string)
    {
        return mysqli_real_escape_string($this->getConnect(), $string);
    } */

    public function numRows($result)
    {
        return mysqli_num_rows($result);
    }

    public function fetchArray($result)
    {
        return mysqli_fetch_assoc($result);
    }

    public function closeConnection()
    {
        sqlsrv_close($this->connect);
    }

}

$database = new DB();
 
