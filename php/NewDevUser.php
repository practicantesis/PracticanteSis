<?php

require('funciones.php');
$forma=NewDevUserForm();

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $forma
);

//echo $forma;

echo json_encode ($jsonSearchResults);
return false;


?>
  
