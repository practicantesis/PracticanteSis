<?php
require('funciones.php');
include('php/configuraciones.class.php');
$db=ConectaSQL('firewall');

//print_r($_POST);

$toctet = GetThirdOctetForOficina($_POST["ofi"],$db);


if ($_POST["valor"] == "lanmac") {
	$q="lanip";
	$success="YES";
}
if ($_POST["valor"] == "wifimac") {
	$q="wifiip";
	$success="YES";
}


if ($toctet == "NO") {
	$nets=CheckMultipleNets($_POST["ofi"]);
	$redes = explode(',', $nets);
	$sele="<select name='seleredes' id='seleredes' onchange=\"CalcularIP('666','".$_POST["valor"]."')\">";
	$sele .= "<option value='SELECCIONE'>SELECCIONE RED</option>";
	foreach ($redes as $valor) {
    	$sele .= "<option value='$valor'>Red $valor</option>";
	}
	$sele .="</select>";
	$success="MULTI";
}


if (preg_match("/\d+/i", $_POST["ofi"] )) {
	$toctet=$_POST["ofi"];
} else {

}





$newIP = GetAvailableIPFromSegment($toctet,$q);
$jsonSearchResults[] =  array(
    'success' => $success,
    'error' => 'err',
    'nvalue' => $newIP,
    'sele' => $sele
);
echo json_encode ($jsonSearchResults);

return false;
?>
