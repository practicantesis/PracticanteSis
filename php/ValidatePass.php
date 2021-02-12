<?php
require('funciones.php');
//print_r($_POST);
if (check_password($_POST['pas'],$_POST['passwd'])) {
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