<?php

echo "Este script se trae todos los TAGS AIRWATCH <br>";

require('php/funciones.php');
//include('configuraciones.class.php');
$celdap=GetCellsFromLDAP("active");
echo "<pre>";
//print_r($celdap);
$imeis=array_keys($celdap);
$conn = ConectaSQL('ocsweb');

$awdevs=QueryToAirwatchAPI("ALLDEVS","ALLDEVS");
$awdevsa=json_decode($awdevs, true);
echo "<pre>";
//print_r($awdevsa);
echo "</pre>";

$all=Array();
$cnt=0;

$con=ConectaLDAP();
echo $con;
$ldaptags=Array();
foreach ($imeis as &$value) {
	$success='DUNNO';
	$valid='DUNNO';
	$tg="";
   	$tg=$celdap[$value]['devicetag'];
   	array_push($ldaptags, $tg);


	/*
	echo $celdap[$value]['devicetag']." Imei: ".$celdap[$value]['deviceimei']."<br>";
    if ($celdap[$value]['deviceimei'] == "PORASIGNAR") {
    	echo "AKI ANDO PAPA!!! ".$celdap[$value]['devicetag']."<br>";
    	//$sn=$celdap[$value]['deviceserial'];
    	$sn=$all[$tg]['serial'];
        echo $celdap[$value]['devicetag']." -> ".$celdap[$value]['deviceimei']."<br>$sn";
        echo "<br>";
		$dn="DeviceTAG=".$tg.",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";        
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
    	//$ldm=ldap_modify($con, $dn, $entry);
    	//$ldm=ldap_mod_replace_ext($con, $dn, $values);
    	//echo $ldm;
    	//echo $RES=ldap_error($con);
        echo "</pre>";
    }
    */
}




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
	$existe="NO SE";
    if ($OCS == "NO TAG ON OCS") {
		//$xxx=GetOCSTAG($valuex['SerialNumber'],$conn);
		if (strlen($xxx) > 3) {
			$xxxx="ACTUAL WRONG TAG ".$xxx;	
			//CorrectOCSTAG($valuex['DeviceFriendlyName'],$xxx,$conn);
		} else {
			$xxxx="OCS TAG TOO SHORT: ".$xxx;	
		}
		$sx=" -> Serie:".$valuex['SerialNumber'];
    }

    if (in_array($valuex['DeviceFriendlyName'], $ldaptags)) {
    	$existe="SI";
    } else {
    	$existe="NO";
    	$ocshwid=GetOCSHwIDFromIMEI($valuex['Imei'],$conn);
    	if (is_numeric($ocshwid)) {
    		$currentOCSTAG=GetOCSTAGFromHwId($ocshwid,$conn);
    		
    		if ($currentOCSTAG == $valuex['DeviceFriendlyName']) {
    			echo "<br>".$valuex['DeviceFriendlyName']." TAG CORRECTO EN OCS $currentOCSTAG <br>";	
    		} else {
    			echo "<br>".$valuex['DeviceFriendlyName']." ERROR!!!! TAG INCORRECTO EN OCS $currentOCSTAG ( ".$valuex['LastSeen']." imei aw ".$valuex['Imei']." sn aw ".$valuex['SerialNumber'].")<br>";	
    		}
    	}
    	if (preg_match("/^CEL(\w\w\w)\d+$/i",$valuex['DeviceFriendlyName'],$matches)) {
$ocsimei=GetOCSImeiFromTag($valuex['DeviceFriendlyName'],$conn);

    			echo $cnt." <-> Tag: ".$valuex['DeviceFriendlyName']." AW IMEI: ".$valuex['Imei']." <-> EXISTE EN LDAP: ".$existe." <-> OCS HWID (FROM IMEI): ".$ocshwid." <-> IMEI on OCS for tag: ".$ocsimei." ".$sx." =  ".$xxxx;
    			$brand=GetBrandFromModel($valuex[ModelId][Name]);
    			$entry=Array();
				$entry['devicebrand']=$brand;
				$entry['devicelastenrolledon']=$valuex['LastEnrolledOn'];
				$entry['devicelastseen']=$valuex['LastSeen'];
				$entry['devicemac']=$valuex['MacAddress'];
				$entry['devicemodel']=$valuex[ModelId][Name];
				$entry['deviceserial']=$valuex['SerialNumber'];
				$entry['deviceoffice']=$matches['1'];
				$entry['deviceassignedto']='PENDIENTE1';
				$entry['devicedept']='Carretera';				
				$entry['deviceimei']=$valuex['Imei'];
				$entry['devicenumber']='PENDIENTE1';
				$entry['devicetag']=$valuex['DeviceFriendlyName'];
				echo $dn="DeviceTAG=".$valuex['DeviceFriendlyName'].",ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
				$entry['objectClass'][0] = "top";
				$entry['objectClass'][1] = "DeviceInfo";
				print_r($entry);
				if ($currentOCSTAG == $valuex['DeviceFriendlyName']) {
					$mod = ldap_add($con, $dn , $entry);
					$ERROR=ldap_error($con);
					echo "<br>$ERROR<br> ";
				} else {
					echo "<br>INCONSISTENCIAS ENTRE TAGS LDAP Y OCS<br> ";
				}

/*
dn: DeviceTAG=CELTRA127,ou=Celulares,ou=Devices,dc=transportespitic,dc=com
*/

				unset($entry);


		} else {
			echo "<br>Saltando tag incorrecto en airwatch: --".$valuex['DeviceFriendlyName']."--<br>";
		}   		

    }

    //echo $cnt." - ".$valuex['DeviceFriendlyName']." IMEI: --> ".$valuex['Imei']." EXISTE EN LDAP: ".$existe." OCS: ----> ".$OCS." ".$sx." = ".$xxxx;
    echo "</pre>";
}


//print_r($all);


echo "Total: ".$cnt;

print_r($ldaptags);

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