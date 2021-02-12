<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
$success='DUNNO';
$valid='DUNNO';

$pila = array();


$value="uid=".$_POST['user'].",ou=People,dc=transportespitic,dc=com";
$success=AppendLDAPVAl($_POST['grp'],$value,"member");



$jsonSearchResults[] =  array(
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;
?>
