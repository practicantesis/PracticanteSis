<?php
require('funciones.php');
//print_r($_POST);

$pass=randomPassword();

$hash=hash_password($pass);

$passmd=md5($pass);

ChgPasswdMamboUser($_POST['user'],$passmd);

ResetPasswordLDAP($_POST['user'],$pass);


if ($response == "SI") {
	$success='YES';
} else {
	$success='NO';
}
$jsonSearchResults[] =  array(
	'pass' => $pass,
	'passct' => "El Password es ".$pass,	
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;
?>
