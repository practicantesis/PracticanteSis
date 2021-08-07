<?php
require_once('clasesql.php');
global $objSQL;
$objSQL = new oracle();
$objSQL->setUsuario("guias");
$objSQL->setPassword("superguias");
$db = $objSQL->conectar();
$q="Select * from RH.empleados";
$result = $objSQL -> setQuery($q);
$renglon = $objSQL -> getNumrows($result);
$c=1;
$ldapconn=ConectaLDAP();


$outx= array();
$ldapfilter="(uid=*)";
if($ldapconn) {
  $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
  if ($ldapbind) {
    $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
    $ldata = ldap_get_entries($ldapconn, $result);
        for ($i=0; $i<$ldata["count"]; $i++) {
            //array_push($outx,$ldata[$i]['noempleado'][0]);
        }
  }
}

return false;

//echo "<pre>";
while ($renglon >= $c){
        $arreglo= $objSQL-> getArray($result);
        //print_r($arreglo);
        //$xxx=LDAPchk($arreglo['CLAVE'],$ldapconn);



       if (in_array($arreglo['CLAVE'], $outx)) {
                $perro="USERNAME EXISTE EN LDAP";
        } else {
                $perro="NO";
        }

        echo  $arreglo['CLAVE'].'--->'.$arreglo['FECHABAJA'].'--->'.$perro."<br>";
        $c++;

}
//echo "</pre>";


?>
