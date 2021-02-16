<?php
require('funciones.php');
//print_r($_POST);

$newIP = DeleteLDAPUser($_POST['dn']);

$lapizlanip='<a href="#" onclick="UVal('."'".$_POST['eldn']."'".','."'lanip'".')"><span class="fa fa-pencil"></span>';



$jsonSearchResults[] =  array(
    'success' => $newIP,
    'lapiz' => $lapizlanip,
    'error' => 'err',
);
echo json_encode ($jsonSearchResults);

return false;
?>
