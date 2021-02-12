<?php
//print_r($_POST);
error_reporting(1);
require('funciones.php');
$ldapserver = 'ldap.tpitic.com.mx';
$ldapuser = 'cn=feria,dc=transportespitic,dc=com';  
$ldappass = 'sistemaspitic';
$ldaptree = "ou=People,dc=transportespitic,dc=com";
$ldapconn = ldap_connect($ldapserver);


$ldapfilter="(uid=".$_POST["user"]."*)";
$aut='';
if($ldapconn) {
  $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
  if ($ldapbind) {
    $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
    $ldata = ldap_get_entries($ldapconn, $result);
    $forma = UserForm($ldata);
  }
}

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $forma[0],
    'alertlan' => $forma[1],
    'alertwifi' => $forma[2],
    'error' => 'err',
);
echo json_encode ($jsonSearchResults);

return false;


?>
  
