<?php

$response = array();
// include db connect class
include ('../connection.php');
global $con;

$client_id=$_GET['client_id'];
$resultIndicator=$_GET['resultIndicator'];
$orderID=$_GET['orderID'];
$type=$_GET['payment_type'];
$client_address_id=$_GET['client_address_id'];
$client_id=$_GET['client_id'];


 $con->query("UPDATE `payment` SET `result`='success' where   `operation_order_id`='$orderID'");
 
if($type&&$type=="credit"){
 header("Location:https://alhamayel.emcan-group.com/finish_register.php?check=credit&operation_order_id=".$orderID."&client_address_id=".$client_address_id.'&client_id='.$client_id);
}
?>

