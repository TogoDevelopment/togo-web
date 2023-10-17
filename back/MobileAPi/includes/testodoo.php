<?php


require_once '../includes/Apis.php';


/*$url = "togo.odoo.com";
$db = "togo";
$username = "rm.mere@gmail.com";
$password = "admin";

*/
require_once 'database.php';

//$url = "http://togo.odoo.com";
//$url = "http://192.168.15.51";
$url = "http://144.91.127.204:8069";
// $url = "http://192.168.14.223";

$db = "togo";
//$username = "rm.mere@gmail.com";
$username = "mmm@mmm.mmm";
$password = "ToGo@2018";
require_once('ripcord-master/ripcord.php');
//$info = ripcord::client('https://demo.odoo.com/start')->start();
//list($url, $db, $username, $password) =array($info['host'], $info['database'], $info['user'], $info['password']);
$common = ripcord::client("$url/xmlrpc/2/common");

print_r($common->version());

$uid = $common->authenticate($db, $username, $password, array());
echo "<br>";
echo $uid;

echo "<br>";
$models = ripcord::client("$url/xmlrpc/2/object");

//diala start


// update info test
$IDNumber = '0400000';
$address = 'jo';
$Email = 'test@gmail.com';
$FirstName = 'test0';
$LastName = 'testing';
$CustomerId = 1245;


$id = $TOGOApp->BalanceIsEnough($CustomerId, 0);
print($id);
/* $var=$TOGOApp->confirm_request($CustomerId,10);
echo 'conf value';
print_r($var);*/
/* $id= $TOGOApp->delivToTrans($CustomerId,10);
  echo 'conf dtt';
  print_r($id);*/
/* $id= $TOGOApp->delivery_request(1247,20,10);
 echo 'conf dr:';
 print_r($id);
 echo "ok";
// print_r($id['togo_discount']);

 // $idc=$TOGOApp->deleteOrderRequest($CustomerId,10);
 // echo "cancel req=".$idc;
  // $idc=$TOGOApp->cancellationfee(1247);
  //echo "cancelation fees= =".$idc;




/* $id= $models->execute_kw($db, $uid, $password,
     'res.partner','cancellation_fees_discount',array('self',1212));
echo"cancel = ";
 print_r($id);*/
/*  $id= $models->execute_kw($db, $uid, $password,
     'res.partner','delivery_request',array('self',1212,50,10));

 print_r($id);*/

/* $id= $models->execute_kw($db, $uid, $password,
     'res.partner', 'confirm_request',array('self',$CustomerId,50));
 print_r($id);*/

//$vvvv=$TOGOApp->confirm_request($CustomerId,30);
//print_r($vvvv);


// print_r ($TOGOApp->getBalance($CustomerId));
// $vvv= $TOGOApp->BalanceIsEnough($CustomerId,200);
//if($vvv==1)echo'ok';
//else echo 'error';


//can_request fun
/*  $varr= $models->execute_kw($db, $uid, $password,
        'res.partner','cancel_request',array('self',1225,30));
echo "vsr-=";
print_r($varr);*/

/* if($varr==1){
     echo 'you have a enguoth balance';
 }else if($varr==-1) echo 'you have to recharge';*/
// recharge fun
/* $varr= $models->execute_kw($db, $uid, $password,
         'res.partner', 'recharge_customer_balance',
         array('self',$CustomerId,'cash',200));
     echo "recharge".$varr;*/


// echo"balance varrrrrr=".$result[0]['customer_id'];
$varr2 = $models->execute_kw($db, $uid, $password,
    'res.partner', 'get_balance', array('self', $CustomerId));
echo " var diala= ";
print_r($varr2);


?>