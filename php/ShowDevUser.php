<?php
//print_r($_POST);
error_reporting(1);
require('funciones.php');
$ldapserver = 'ldap.tpitic.com.mx';
$ldapuser = 'cn=feria,dc=transportespitic,dc=com';  
$ldappass = 'sistemaspitic';
$ldaptree = "ou=DeviceUsers,dc=transportespitic,dc=com";
$ldapconn = ldap_connect($ldapserver);

$ldapfilter="(duusernname=".$_POST["user"]."*)";
$aut='';
if($ldapconn) {
  $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
  if ($ldapbind) {
    $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
    $ldata = ldap_get_entries($ldapconn, $result);
    $forma = DevUserForm($ldata);
  }
}

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $forma,
    'error' => 'err',
);
echo json_encode ($jsonSearchResults);

return false;


?>
  
