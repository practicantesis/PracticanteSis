<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
$exdu=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusernname",$_POST['value']);

if ($exdu == "YES") {
	$success="NO";
	$err="El device user ".$_POST['value']."no lo conocen ni en su casa";

}

$extel=CheckExistentValueLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceassignedto",$_POST['value']);


if ($extel == "NO") {
	$success="NO";
	$err="El device user ".$_POST['value']." ya tiene telefono, para que quiere otro?";

}

//echo "el valor es  $extelxxx";


//return false;




$jsonSearchResults[] =  array(
    'success' => $success,
    'err' => $err,
);
echo json_encode ($jsonSearchResults);
return false;

?>
