<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
$success='DUNNO';
$success=CheckExistentValueLDAP($LDAPBase,$_POST['what'],$_POST['value']);



$jsonSearchResults[] =  array(
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;

?>
