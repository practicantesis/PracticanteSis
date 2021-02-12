<?php

require('funciones.php');
$forma=NewUserForm();

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $forma
);

//echo $forma;

echo json_encode ($jsonSearchResults);
return false;


?>
  
