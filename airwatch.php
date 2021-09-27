<?php

require('php/funciones.php');

//echo $out=QueryToAirwatchAPI("ALLDEVS","NO");

//$out=QueryToAirwatchAPI("DEVICE","MXRNU19802100085");
//$array=json_decode($out, true);
//echo $array['LastSeen'];
echo "<pre>";
//print_r($array);
echo "</pre>";


$celdap=GetCellsFromLDAP();
echo "<pre>";
	//print_r($celdap);

$series=array_keys($celdap);

foreach ($series as &$value) {
	$out='';
	$eq="DUNNO";
	$oarray='';
	print "Serie: ".$value;
	
	$out=QueryToAirwatchAPI("DEVICE",$value);
	if ($out['lastseen'] == $celdap[$value]['devicelastseen']) {
		$eq="IGUALES";
	} else {
		$eq="DIFERENTES";
	}
	$oarray=json_decode($out, true);
	$len=strlen ($oarray['LastSeen']);
	if ($len == 0) {
		$oarray['LastSeen']="No Encontrado en API";
	}
	print " TAG: ". $celdap[$value]['devicetag']." Last seen en LDAP --> ".$celdap[$value]['devicelastseen']." Last Seen en ONE: ".$oarray['LastSeen'];
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