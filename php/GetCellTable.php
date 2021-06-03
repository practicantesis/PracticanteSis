<?php
include('configuraciones.class.php');
require('funciones.php');

$con=ConectaLDAP();
$conx=ConectaSQL('ocsweb');
set_time_limit(30);
$result = ldap_search($con,"ou=Celulares,ou=Devices,dc=transportespitic,dc=com", "(DeviceTAG=*)");
$err=ldap_error($con);
$ldata = ldap_get_entries($con, $result);
$fnt1="<font face='Trebuchet MS, Arial, Helvetica' size='1'>";
$fnt2="<font face='Trebuchet MS, Arial, Helvetica' size='2'>";


$html  = "<table class='table table-striped table-bordered' id='celltable'><thead>";
$html .= "<tr><th>Tag</th><th>Assignedto</th><th>Brand</th><th>Dept</th><th>Imei</th><th>IP</th><th>lastenroll</th><th>lastseen</th><th>mac</th><th>office</th><th>Serial</th><th>ofi en user</th><th>no emp en user</th><th>nombre<en deviceuser/td><th>OCS TAG</th><th>OCS HW ID</th></tr></thead><tbody>";

for ($i=0; $i<$ldata["count"]; $i++) {
    $html .= "<tr>";
	$html .= "<td>$fnt2".$ldata[$i]['devicetag'][0]."</td>";
    $html .= "<td>$fnt2".$ldata[$i]['deviceassignedto'][0]."</td>";
    $html .= "<td>$fnt2".$ldata[$i]['devicebrand'][0]."</td>";
	$html .= "<td>$fnt2".$ldata[$i]['devicedept'][0]."</td>";
    $html .= "<td>$fnt1".$ldata[$i]['deviceimei'][0]."</td>";
    $html .= "<td>$fnt1".$ldata[$i]['deviceip'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['devicelastenrolledon'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['devicelastseen'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['devicemac'][0]."</td>";    	
	$html .= "<td>$fnt2".$ldata[$i]['deviceoffice'][0]."</td>";    	
	$html .= "<td>$fnt1".$ldata[$i]['deviceserial'][0]."</td>";    	



	////////////////////////////echo $n=GetDevUserFromLDAP($ldata[$i]['deviceassignedto'][0],$con);	


//$ocstag=GetOCSTAG($ldata[$i]['deviceserial'][0],$conx);
//$html .= "<td>$fnt1*** ".$ocstag."</td>";      
$html .= "<td></td><td></td><td></td><td></td><td></td>";      
//echo $ocstag;      

    $html .= "</tr>";
    
}
$html .= "</tbody></table>";



$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $html
);

//echo $forma;

echo json_encode ($jsonSearchResults);
return false;


?>
  
