<?php

require('../../php/funciones.php');

echo "<strong>Este Reporte busca todos celulares que tengan deviceoffice diferente a BAJA_CEL* y busca el valor de LastSeen en Airwatch<br></strong>";

$celdap=GetCellsFromLDAP("all");

echo "<pre>";
//print_r($celdap);
echo "</pre>";

$imeis=array_keys($celdap);

$cnt=0;

foreach ($imeis as &$value) {
	#if (strlen($celdap[$value]['devicetag']) > 1) {
		$out='';
		$eq="DUNNO";
		$oarray='';
		print "TAG:".$celdap[$value]['devicetag']." -> Imei (LDAP): ".$value." -> Oficina:".$celdap[$value]['deviceoffice']."<br>";
/*
		$out=QueryToAirwatchAPI("DEVICEperIMEI",$value);
		$cnt++;
		$oarray=json_decode($out, true);
		if (strlen($out) == 0) {
			echo "<big><big><p style='color:red'>IMEI No Encontrado en API</p></big></big><br>";
		} else {
			echo "Seen:".$oarray['LastSeen']."<br>";
		}
*/
	#}
}


echo "<br>Total: ".$cnt;

return false;

/*
TAG:CELHLO100
foreach ($imeis as &$value) {

	
	$arrayuser=GetDeviceUserInfoFromLDAP($celdap[$value]['deviceassignedto']);
	//print_r($arrayuser);
	//echo "cccccccccccccc".$arrayuser[0]['dunombre'][0]."iiiiiiiiiiiiiiiiii";

if (preg_match('/BAJA/', $celdap[$value]['deviceoffice'], $matches)) {

} else {
	print " TAG: ". $celdap[$value]['devicetag']." Last seen en LDAP --> ".$celdap[$value]['devicelastseen']." Last Seen en ONE: ".$oarray['LastSeen'].' OFICINA: '.$celdap[$value]['deviceoffice'];

}
	
	$arrayuser[0]['dunombre'][0] = trim(preg_replace('/\s\s+/', ' ', $arrayuser[0]['dunombre'][0]));
	$celdap[$value]['deviceassignedto'] = trim(preg_replace('/\s\s+/', ' ', $celdap[$value]['deviceassignedto']));


	if (strlen($arrayuser[0]['dunumeroempleado'][0]) < 1) {
		$arrayuser[0]['dunumeroempleado'][0] = 0000;
	}

if (preg_match('/BAJA/', $celdap[$value]['deviceoffice'], $matches)) {

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



echo "</pre>";

$out=QueryToAirwatchAPI("DEVICE","MXRNU19802100085");
$array=json_decode($out, true);
print_r($array);
echo "</pre>";


$cells=GetDeviceListFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","activetag");



$cnt=0;
foreach ($cells as &$tag) {
	$ofi="";
	//$ofi=GetDeviceInfoFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceofi",$tag);
	echo $tag." -> $ofi <br>";
	$cnt++;
}	








echo "<pre>";
print_r($cells);
echo "</pre>";


echo "<pre>";
print_r($celdap);
echo "</pre>";

//echo $out=QueryToAirwatchAPI("ALLDEVS","NO");
//$out=QueryToAirwatchAPI("DEVICE","MXRNU19802100085");
//$array=json_decode($out, true);
//echo $array['LastSeen'];
echo "<pre>";
//print_r($array);
echo "</pre>";
$fp = fopen('./work/lidn.txt', 'w');
$celdap=GetCellsFromLDAP("active");
echo "<pre>";
//print_r($celdap);

	$serie=$celdap[$value]['deviceserial'];


	echo "<pre>";
	print_r($oarray);
	echo "</pre>";


*/
?>
