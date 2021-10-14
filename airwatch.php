<?php

require('php/funciones.php');
//echo $out=QueryToAirwatchAPI("ALLDEVS","NO");
//$out=QueryToAirwatchAPI("DEVICE","MXRNU19802100085");
//$array=json_decode($out, true);
//echo $array['LastSeen'];
echo "<pre>";
//print_r($array);
echo "</pre>";
$fp = fopen('./work/lidn.txt', 'w');
$celdap=GetCellsFromLDAP();
echo "<pre>";
//print_r($celdap);
$imeis=array_keys($celdap);
foreach ($imeis as &$value) {
	$out='';
	$eq="DUNNO";
	$oarray='';
	print "Imei: ".$value;
	$serie=$celdap[$value]['deviceserial'];
	$out=QueryToAirwatchAPI("DEVICE",$serie);
	if ($out['lastseen'] == $celdap[$value]['devicelastseen']) {
		$eq="IGUALES";
	} else {
		$eq="DIFERENTES";
	}
	$oarray=json_decode($out, true);
	//echo "<pre>";
	//print_r($oarray);
	//echo "</pre>";
	//sleep(33);
	$len=strlen ($oarray['LastSeen']);
	if ($len == 0) {
		$oarray['LastSeen']="No Encontrado en API";
	}
	$arrayuser=GetDeviceUserInfoFromLDAP($celdap[$value]['deviceassignedto']);
	//print_r($arrayuser);
	//echo "cccccccccccccc".$arrayuser[0]['dunombre'][0]."iiiiiiiiiiiiiiiiii";
print " TAG: ". $celdap[$value]['devicetag']." Last seen en LDAP --> ".$celdap[$value]['devicelastseen']." Last Seen en ONE: ".$oarray['LastSeen'];
	
	$arrayuser[0]['dunombre'][0] = trim(preg_replace('/\s\s+/', ' ', $arrayuser[0]['dunombre'][0]));
	$celdap[$value]['deviceassignedto'] = trim(preg_replace('/\s\s+/', ' ', $celdap[$value]['deviceassignedto']));


	if (strlen($arrayuser[0]['dunumeroempleado'][0]) < 1) {
		$arrayuser[0]['dunumeroempleado'][0] = 0000;
	}

if (preg_match('/BAJA/', $arrayuser[0]['duoficina'][0], $matches)) {

} else {
	fwrite($fp, $celdap[$value]['devicetag'].'###'.$oarray['LastSeen']."###".$serie."###".$oarray['Id']['Value']."###".$arrayuser[0]['dunumeroempleado'][0]."###".$arrayuser[0]['duoficina'][0]."###".$arrayuser[0]['dunombre'][0]."###".$celdap[$value]['deviceassignedto']."###".$celdap[$value]['deviceoffice']."###\n");
	
}

	
	//echo "XXXXXXXXXXXXXXXXXXXXXXXX<pre>";
	//print_r($out);
	//echo "</pre>YYYYYYYYYYYYYYYYYYYYYY";
	//sleep(20);
	//print_r(array_keys($array));

	print "<br>";
}	

/*
foreach ($celdap as &$value) {
    foreach ($value as &$valueb) {
    	print $valueb;
    	print "<br>";
    }
}
*/



echo "</pre>";
/*
$out=QueryToAirwatchAPI("DEVICE","MXRNU19802100085");
$array=json_decode($out, true);
print_r($array);
echo "</pre>";
*/

?>
