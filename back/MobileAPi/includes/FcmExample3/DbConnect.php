<?php

//Class DbConnect
class DbConnect
{
    //Variable to store database link
    private $con;

    //Class constructor
    function __construct()
    {

    }

    //This method will connect to the database
    function connect()
    {

        require_once '../../../database.php';

        //finally returning the connection link 
        return $this->con;
    }

}