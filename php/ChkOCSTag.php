<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
error_reporting(1);
$success='DUNNO';
//$success=CheckExistentValueLDAP($LDAPBase,$_POST['what'],$_POST['value']);

$hwid=GetHardwareIDFromUsername($_POST['valor']);



//if ($hwid != "NO") {
	$lanmac=GetAvailHardware('lanmac',$_POST['valor']);
	$wifimac=GetAvailHardware('wifimac',$_POST['valor']);
//}
$success="YES";


$jsonSearchResults[] =  array(
    'success' => $success,
    'valor' => $hwid,
    'lanmac' => $lanmac,
    'wifimac' => $wifimac,
);
echo json_encode ($jsonSearchResults);
return false;

?>

