<?php
class TransporterService
{
    private $dataBase;

    /**
     * TransporterService constructor.
     * @param $dataBase
     */
    public function __construct($dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getTransporter($transporterId)
    {
        $transporter = array();
        $query = "select customer.PhoneNumber, transportertable.Email, concat(transportertable.FirstName, ' ',
		transportertable.LastName) as fullname,transportertable.IsTeamActivated as IsTeamActivated,transportertable.TeamId as TeamId  
        from togo.customer as customer
        inner join togo.transportertable as transportertable on customer.id = transportertable.CustomerId 
        where customer.id='$transporterId'";

        $result = $this->dataBase->query($query);
		//$TeamId = $result['TeamId'];
		//$query_team_name = "select Name from teams where id='$TeamId'";
        //$result_team_name = $this->dataBase->query($query_team_name);
		
        while ($row = $this->dataBase->fetchArray($result)) {
            array_push($transporter, $row);
        }
        return $transporter;
    }
}