<?php

require('funciones.php');
//print_r($_POST);

$celdap=GetCellsFromLDAP("active");
$ldaptags = Array();
$ldaptagsid = Array();

foreach ($celdap as &$value) {
    if (preg_match("/".$_POST['ofi']."(\d+)/i",$value[devicetag],$matches)) {
        //echo $value[devicetag]."<br>";
        //echo $matches[1];
        array_push($ldaptags, $value[devicetag]);
        array_push($ldaptagsid, $matches[1]);
    }        
}    

$max=max($ldaptagsid);
$data = "Nota: Este tag es el ultimo en LDAP, asegurese que ha sincronizado LDAP con airwatch antes de correr el calculo<br><big><strong>el proximo TAG es ".($max+1)."</strong></big><br> Tags Existentes:";

sort($ldaptags);
$data .= "<pre>";
$data .= print_r($ldaptags, TRUE);
$data .= "</pre>";


//echo $data;



//print_r($ldaptags);



$jsonSearchResults[] =  array(
	'success' => 'YES',
	'data' => $data,
	'error' => $err,
);
echo json_encode ($jsonSearchResults);

return false;

?>



