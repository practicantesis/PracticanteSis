<?php
require('funciones.php');
include('configuraciones.class.php');
$success='DUNNO';
$valid='DUNNO';
$con=ConectaLDAP();

foreach ($_POST['data'] as $value) {
  $vname=$value[name];
  $forma[$vname]=$value[value];
} 
$dn="DeviceTAG=".$forma['val-newtag'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
$ERROR="NO";
$entry['deviceassignedto']=$forma['val-deviceassignedto'];
$entry['devicenumber']=$forma['val-devicenumber'];
$entry['deviceoffice']=$forma['val-oficina'];
$entry['devicedept']=$forma['val-devicedept'];
$entry['deviceimei']=$forma['val-devicimei'];
$entry['objectClass'][0] = "top";
$entry['objectClass'][1] = "DeviceInfo";


/*
echo "<pre>";
print_r($entry);
echo "</pre>";
echo $ERROR;
return false;
*/


if ($ERROR == "NO") {
 	$mod = ldap_add($con, $dn , $entry);
	$ERROR=ldap_error($con);
}

$jsonSearchResults[] =  array(
    'success' => $ERROR
);
echo json_encode ($jsonSearchResults);
return false;


?>


