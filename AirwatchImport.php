<?php

require('php/funciones.php');
//include('configuraciones.class.php');
$celdap=GetCellsFromLDAP("x");
echo "<pre>";
//print_r($celdap);

echo "Este scripi se trae todos los devices de Airwatch <br>";

$imeis=array_keys($celdap);
$conn = ConectaSQL('ocsweb');

$awdevs=QueryToAirwatchAPI("ALLDEVS","ALLDEVS");
$awdevsa=json_decode($awdevs, true);
echo "<pre>";
//print_r($awdevsa);
echo "</pre>";

$all=Array();
$cnt=0;

foreach ($awdevsa['Devices'] as &$valuex) {
	$cnt++;
	$fn="";
	$din="";
    echo "<pre>";
    //print_r($valuex);
    if (strlen($valuex['DeviceFriendlyName']) > 7 ) {
	    if (strlen($valuex['DeviceFriendlyName']) < 10 ) {
			$OCS=GetOCSInfoFromTAG($valuex['DeviceFriendlyName'],"HARDWARE_ID",$conn);
			$fn=$valuex['DeviceFriendlyName'];
			$all[$fn]['serial']=$valuex['SerialNumber'];
	    } else {
	    	// Si el tag esta mal buscar la serie en LDAP
	    	$din=GetDeviceInfoFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceserial",$valuex['SerialNumber']);
	    	$mytg=$din[0]['devicetag'];
	    	$OCS="INVALID TAG, NOT CHECKING (TAG= $mytg sn: ".$valuex['SerialNumber']." )";
	    	//print_r($din);
	    }
    } else {
    	$OCS="INVALID TAG, NOT CHECKING";	
    }
	$sx='';
	$xxx='';
	$xxxx='';
    if ($OCS == "NO TAG ON OCS") {
		$xxx=GetOCSTAG($valuex['SerialNumber'],$conn);
		if (strlen($xxx) > 3) {
			$xxxx="ACTUAL WRONG TAG ".$xxx;	
			CorrectOCSTAG($valuex['DeviceFriendlyName'],$xxx,$conn);
		} else {
			$xxxx="ACTUAL WRONG TAG TOO SHORT OR NOT FOUND";	
		}
		$sx=" -> Serie:".$valuex['SerialNumber'];
    }

    echo $cnt." xx".$valuex['DeviceFriendlyName']."yy  IMEI: --> ".$valuex['Imei']."  OCS: ----> ".$OCS." ".$sx." = ".$xxxx;
    echo "</pre>";
}


//print_r($all);
$con=ConectaLDAP();
echo $con;

foreach ($imeis as &$value) {
	$success='DUNNO';
	$valid='DUNNO';
	$tg="";
    if ($celdap[$value]['deviceimei'] == "PORASIGNAR") {
    	//$sn=$celdap[$value]['deviceserial'];
    	$tg=$celdap[$value]['devicetag'];
    	$sn=$all[$tg]['serial'];
        echo $celdap[$value]['devicetag']." -> ".$celdap[$value]['deviceimei']."<br>$sn";
        echo "<br>";
		echo $dn="DeviceTAG=".$tg.",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";        
        $telinfo=QueryToAirwatchAPI("DEVICE",$sn);    
        //echo "<pre> $telinfo </pre>";
        $telia=json_decode($telinfo, true);
        echo "<pre>";
        //print_r($telia);
		$brand=GetBrandFromModel($telia[ModelId][Name]);
		$entry = array();
		$entry['devicebrand']=$brand;
		$entry['deviceimei']=$telia['Imei'];
		$entry['devicelastenrolledon']=$telia['LastEnrolledOn'];
		$entry['devicelastseen']=$telia['LastSeen'];
		$entry['devicemac']=$telia['MacAddress'];
		$entry['devicemodel']=$telia[ModelId][Name];
		$entry['deviceserial']=$telia['SerialNumber'];
		$entry['objectClass'][0] = "top";
		$entry['objectClass'][1] = "DeviceInfo";
		print_r($entry);  
    	$ldm=ldap_modify($con, $dn, $entry);
    	//$ldm=ldap_mod_replace_ext($con, $dn, $values);
    	echo $ldm;
    	echo $RES=ldap_error($con);
        echo "</pre>";
    }
}


echo "Total: ".$cnt;

/*




DeviceModel
DeviceSerial

$entry['deviceimei']=$forma['val-devicimei'];


$brand=GetBrandFromModel($telia[ModelId][Name]);
$entry['devicebrand']=$brand;
$entry['devicelastenrolledon']=$telia['LastEnrolledOn'];
$entry['devicelastseen']=$telia['LastSeen'];
$entry['devicemac']=$telia['MacAddress'];
$entry['devicemodel']=$telia[ModelId][Name];
$entry['deviceserial']=$telia['SerialNumber'];
$entry['objectClass'][0] = "top";
$entry['objectClass'][1] = "DeviceInfo";



echo "<pre>";
print_r($entry);
echo "</pre>";
echo $ERROR;
return false;


*/