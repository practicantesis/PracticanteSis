<?php
require('../php/funciones.php');

$conn=ConectaSQL('globaldb');

//https://192.168.100.2/usrv/outreporte.txt

$ldapserver = 'ldap.tpitic.com.mx';
$ldapuser = 'cn=feria,dc=transportespitic,dc=com';  
$ldappass = 'sistemaspitic';
$ldaptree = "ou=People,dc=transportespitic,dc=com";
$ldapconn = ldap_connect($ldapserver);

$outx= array();
$ldapfilter="(uid=*)";
if($ldapconn) {
  $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
  if ($ldapbind) {
    $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
    $ldata = ldap_get_entries($ldapconn, $result);
	for ($i=0; $i<$ldata["count"]; $i++) {
	    array_push($outx,$ldata[$i]['uid'][0]);
	}
  }
}
echo "<pre>";
//print_r($outx);
echo "</pre>";



$sql="SELECT * FROM only_users";
$result = $conn->query($sql);

$out="<table border='1'>";

while($row = $result->fetch_assoc()) {
	$perro="";
	if (in_array($row['username'], $outx)) {
		$perro="USERNAME EXISTE EN LDAP";
	} else {
		$perro="NO";
	}
	$out .= "<tr>";
	$out .= "<td>".$row['id']."</td>";
	$out .= "<td>".$row['nombre']."</td>";
	$out .= "<td>".$row['username']."</td>";
	$out .= "<td>".$row['oficina']."</td>";
	$out .= "<td>".$perro."</td>";
	$out .= "</tr>";
	echo "<pre>";
	//print_r($row);
	echo "</pre>";
}

$out .= "</table>";


$out.="SOLO ESISTENTES";


$out.="<table border='1'>";

while($row = $result->fetch_assoc()) {
	$perro="";
	if (in_array($row['id'], $outx)) {
		$perro="EXISTE EN LDAP";
	$out .= "<tr>";
	$out .= "<td>".$row['id']."</td>";
	$out .= "<td>".$row['nombre']."</td>";
	$out .= "<td>".$row['username']."</td>";
	$out .= "<td>".$row['oficina']."</td>";
	$out .= "<td>".$perro."</td>";
	$out .= "</tr>";

	} else {
		$perro="NO";
	}
	echo "<pre>";
	//print_r($row);
	echo "</pre>";
}
$out .= "</table>";



echo $out;
?>