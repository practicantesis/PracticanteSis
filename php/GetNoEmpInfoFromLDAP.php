<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
error_reporting(1);
$success='DUNNO';
//echo "zz";
$empinfo=GetNoEmpInfoFromLDAP($_POST['valor'],'array');

$success="YES";


$jsonSearchResults[] =  array(
    'success' => $success,
    'valor' => $empinfo,
    'uid' => $empinfo[uid],
    'cn' => $empinfo[cn],
    'oficina' => $empinfo[oficina]
    
);
echo json_encode ($jsonSearchResults);
return false;

?>

