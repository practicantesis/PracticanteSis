<?php
include 'configuraciones.class.php';
require('funciones.php');

$conn=ConectaSQL('firewall');
//$conn=$db;
$tb ="<div class='table-responsive'><table class='table table-striped' name='TablaVPN' id='TablaVPN'><thead><tr><th>user</th><th>ipvpn</th><th>hora</th><th>tiempo</th><th>ipinternet</th><th>recibidos</th><th>enviados</th><th>tipo</th><th>identificador</th><th>ofi</th></thead></tr><tbody>";
$sql = "select * from eventosvpn";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tb .="<tr><td>".$row["user"]."</td><td>".$row["ipvpn"]."</td><td>".$row["hora"]."</td><td>".$row["tiempo"]."</td><td>".$row["ipinternet"]."</td><td>".$row["recibidos"]."</td><td>".$row["enviados"]."</td><td>".$row["tipo"]."</td><td>".$row["identificador"]."</td><td>".$row["ofi"]."</td></tr>";
            
        }
    }

$tb .="</tbody></table></div>";

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'error' => 'err',
    'data' => $tb,
);
echo json_encode ($jsonSearchResults);

return false;
?>
