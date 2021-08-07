<?php
require('funciones.php');
//print_r($_POST);

$nets=CheckMultipleNets($_POST['ofi']);
$asignado="NO";
if ($nets != "NO") {
	$sele='';
	$multi='';
	$toctet='';
	$redes = explode(',', $nets);
}

if(preg_match("/^\d+$/",$_POST['multi'])) {
	$toctet=$_POST['multi'];
	$asignado="SI";
} else {
	//echo "aki ando nets es: $nets -  multi es: $multi ";
	if ($nets == "NO") {
		// Hacemos update a lanmac y lanip
		if ($_POST['tipo'] == "lanmac") {
			UpdateLDAPVAl($_POST['dn'],$_POST['multi'],'lanmac');
			if (filter_var($_POST['lanip'], FILTER_VALIDATE_IP)) {
			    //echo($_POST['lanip']." is a valid IP address");
			    UpdateLDAPVAl($_POST['dn'],$_POST['lanip'],'lanip');
			}			

		}
	}		
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

/*



$newIP = GetAvailableIPFromSegment($toctet,'lanip');


*/

return false;
?>
