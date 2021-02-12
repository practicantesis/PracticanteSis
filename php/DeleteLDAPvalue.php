<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
//return false;
$success='DUNNO';
$valid='DUNNO';

$pila = array();

/*

if ($_POST['value'] == "aliascuentagoogle") {
	if ((filter_var($_POST['nvalue'] , FILTER_VALIDATE_EMAIL)) or ($_POST['nvalue'] == "NO")) {
	    //echo("$ip is a valid IP address");
	    $valid="YES";
	} else {
	    $success=$_POST['nvalue']."No es una direccion de correo valida";
	}
}

$ldapBind=ConectaLDAP();	
//$sr=ldap_read($ldapBind,,(objectClass=*),["aliascuentagoogle"],array(""));
$sr = @ldap_read($ldapBind, $_POST['dn'], 'objectClass=*', array($info));
$info = ldap_get_entries($ldapBind, $sr);
echo "yx";
print_r($info);
echo "xy";
*/

//if ($valid=="YES") {
	$success=DeleteLDAPVAl($_POST['dn'],$_POST['nvalue'],$_POST['value']);
	//echo "xxx $success yyy";
//}



$jsonSearchResults[] =  array(
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;
?>
