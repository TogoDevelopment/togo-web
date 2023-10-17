<?php

/* edited (OdooApi class added to reach Odoo APIs in the path: /usr/lib/python3/dist-packages/odoo/addons/custom_account/controllers, which contains functions to get the financial-transactions) */
class OdooService_2
{
    protected $_cookieFileLocation = './Odoocookie.txt';
    protected $_header = array(
        'Content-Type: application/json'
    );
    protected $cr; // curl cursor
    protected $url;

    public function __destruct()
    {
        curl_close($this->cr);
    }

    public function __construct()
    {
        $this->_cookieFileLocation = str_replace('//', '/', dirname(__FILE__) . '/') . '/Odoocookie.txt';
        $this->cr = curl_init();
        $this->url = "http://46.253.95.70";

        // authenticate:

        $params = json_encode(array(
            "jsonrpc" => "2.0",
            "params" => array("db" => "ToGo", "login" => "admin", "password" => "admin")
        ));

        $this->callOdooUrl("/web/session/authenticate", "POST", $params);
    }

    public function callOdooUrl($url = null, $method = null, $params = null)
    {
        if (!$url) {
            throw new Exception('You should set an URL to call.');
        }
        if (!$method)
            throw new Exception('You should set a method to call.');
        curl_setopt($this->cr, CURLOPT_URL, $this->url . $url);
        curl_setopt($this->cr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->cr, CURLOPT_ENCODING, '');
        curl_setopt($this->cr, CURLOPT_MAXREDIRS, 30);
        curl_setopt($this->cr, CURLOPT_MAXREDIRS, 0);
        curl_setopt($this->cr, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
        curl_setopt($this->cr, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
        curl_setopt($this->cr, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->cr, CURLOPT_POSTFIELDS, $params);
        curl_setopt($this->cr, CURLOPT_HTTP_VERSION, true);
        curl_setopt($this->cr, CURLOPT_FAILONERROR, true);
        curl_setopt($this->cr, CURLOPT_HTTPHEADER, $this->_header);
        curl_setopt($this->cr, CURLOPT_CUSTOMREQUEST, $method);
        $data = curl_exec($this->cr);
        $status = curl_getinfo($this->cr, CURLINFO_HTTP_CODE);
        if (curl_errno($this->cr)) {
            $msg = curl_error($this->cr);
            return [
                'status' => 0,
                'message' => $msg
            ];
        } else {
            return [
                'status' => 1,
                'data' => json_decode($data)
            ];
        }
    }
}

class OdooService
{

    private $url;
    private $db;
    private $username;
    private $password;
    private $common;
    private $uid;
    private $models;

    public function __construct()
    {
        $this->OdooDb();
    }

    private function OdooDb()
    {
        try {
            require_once(str_replace('//', '/', dirname(__FILE__) . '/') . '../ripcord-master/ripcord.php');
            $this->url = "http://46.253.95.70";
            $url = "http://46.253.95.70";
            $this->db = "ToGo";
            $this->username = "admin";
            $this->password = "admin";
            $this->common = ripcord::client("$url/xmlrpc/2/common");
            $this->uid = $this->common->authenticate($this->db, $this->username, $this->password, array());
            $this->models = ripcord::client("$url/xmlrpc/2/object");
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function getBalance($customerId)
    {
        $id = $customerId;
        $result = $this->models->execute_kw($this->db, $this->uid, $this->password, 'res.partner', 'get_balance', array('self', $id));
        return $result;
    }

    public function createNewPartner($CustomerId, $PhoneNumber, $typeCustomer, $IDNo, $email, $name)
    {
        $result = $this->models->execute_kw(
            $this->db,
            $this->uid,
            $this->password,
            'res.partner',
            'create_new_partner',
            array('self', $CustomerId, $PhoneNumber, $typeCustomer, $IDNo, $email, $name)
        );
        return $result;
    }

    public function releaseEscrow($togoCustomerId, $orderId, $amount, $discountRatio)
    {
        $this->OdooDb();
        $result = $this->models->execute_kw($this->db, $this->uid, $this->password, 'res.partner', 'release_escrow', array('self', $togoCustomerId, $discountRatio, $orderId, $amount));
        return $result;
    }

    public function move_to_escrow($togoCustomerId, $orderId, $amount)
    {
        $this->OdooDb();
        $conreq = $this->models->execute_kw($this->db, $this->uid, $this->password, 'res.partner', 'move_to_escrow', array('self', $togoCustomerId, $orderId, $amount));
        // print_r($conreq);
    }

    public function BalanceIsEnough($togoCustomerId, $amount)
    {
        $this->OdooDb();
        $resultt = $this->models->execute_kw($this->db, $this->uid, $this->password, 'res.partner', 'can_request', array('self', $togoCustomerId, $amount));

        return $resultt;
    }
}
