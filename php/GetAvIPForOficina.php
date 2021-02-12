<?php
require('funciones.php');
include('php/configuraciones.class.php');
$db=ConectaSQL('firewall');

//print_r($_POST);

$toctet = GetThirdOctetForOficina($_POST["ofi"],$db);

if ($_POST["valor"] == "lanmac") {
	$q="lanip";
}
if ($_POST["valor"] == "wifimac") {
	$q="wifiip";
}

$newIP = GetAvailableIPFromSegment($toctet,$q);
$jsonSearchResults[] =  array(
    'success' => 'YES',
    'error' => 'err',
    'nvalue' => $newIP
);
echo json_encode ($jsonSearchResults);

return false;
?>
