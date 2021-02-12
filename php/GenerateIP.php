<?php
require('funciones.php');
//print_r($_POST);
$nets=CheckMultipleNets($_POST['valueofi']);
$sele='';
$multi='';
$toctet='';
$asignado="NO";
if ($nets != "NO") {
	$redes = explode(',', $nets);
}

if(preg_match("/^\d+$/",$_POST['multi'])) {
	$toctet=$_POST['multi'];
	$asignado="SI";
} else {
	if ($nets != "NO") {
		$multi='YES';
		$sele="<select name='seleredes' id='seleredes'>";
		$sele .= "<option value='SELECCIONE'>SELECCIONE RED</option>";
		foreach ($redes as $valor) {
	    	$sele .= "<option value='$valor'>Red $valor</option>";
		}
		$sele .="</select><button type='button' class='btn btn-primary mb-2' onclick=\"SVal('lanip')\">Selecciona</button>";
	}
}

$newIP = GetAvailableIPFromSegment($toctet,'lanip');

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'error' => 'err',
    'newip' => $newIP,
    'multi' => $multi,
	'nets' => $nets,
	'asignado' => $asignado,
    'sele' => $sele   
);
echo json_encode ($jsonSearchResults);

return false;
?>
