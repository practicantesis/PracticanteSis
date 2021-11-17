<?php
require('funciones.php');
include('configuraciones.class.php');

//print_r($_POST);

if ($_POST['initial'] == "OFF") {
	$success=UpdateLDAPVAl($_POST['dn'],$_POST['nuevo'],'livemeeting');	
}

if ($_POST['initial'] == "ON") {
	$success=UpdateLDAPVAl($_POST['dn'],'NO','livemeeting');	
}


$jsonSearchResults[] =  array(
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;


?>
