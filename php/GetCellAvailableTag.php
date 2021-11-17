<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
error_reporting(1);
$success='DUNNO';
//echo "zz";

$devs=GetFilteredDeviceListFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","deviceoffice",$_POST['ofi'],"devicetag");
$numbas=Array();

foreach ($devs as $value) {
    if (preg_match("/([A-Z]+)(\d+)/i",$value,$mat)) {
        //echo "value es $value ".$mat['1']."\n";
        array_push($numbas, $mat['2']);
        $tagbody=$mat['1'];
    }
}


$elmax=(max($numbas)+1);
//echo "el max es ".$tagbody.$elmax;
//print_r($numbas);

//print_r($devs);

//$ofi=GetCellAvailableTag($_POST['ofi']);

if (strlen($elmax) > 0) {
	$success="YES";	
} else {
	$success="NO";	
}



$jsonSearchResults[] =  array(
    'success' => $success,
    'tag' => $tagbody.$elmax
    
);
echo json_encode ($jsonSearchResults);
return false;

?>

