<?php
require('funciones.php');
//print_r($_POST);
$response=ValidatePassword($_POST['pas'],$_POST['passwd']);

if ($response == "SI") {
	$success='YES';
} else {
	$success='NO';
}
$jsonSearchResults[] =  array(
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;
?>
