<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="http://192.168.120.179/INFRAESTRUCTURA-DESARROLLO/gsalazar/tabla/">Regresar</a>
    <center>
        <form action="airwatchdos.php" method="post">
           <label for="">Numero de serie: </label> <input type="text" id="lname" name="serie">
            <input type="submit" value="Buscar">
        </form>
        <p>Ejemplos:</p>
        <p>MXRNU19802100085</p>
        <p>MXRNU19720104463</p>
    </center>
<?php
require('php/funciones.php');
$buscar = $_POST['serie'];
$out = QueryToAirwatchAPI("DEVICE", $buscar); //MXRNU19802100085
$array = json_decode($out, true);
echo '<center>';
echo '<table border=1 class="default">';
echo '<thead"><tr><th>Usuario</th><th>Correo</th><th>Nom. de grupo</th><th>Num. serie</th><th>Bateria</th><th>Modelo</th><th>Ultima conexion</th></th></tr></thead>';
    echo '<tbody><tr><td>' . $array["UserName"] . '</td>';
    echo '<td>' . $array["UserEmailAddress"] . '</td>';
    echo '<td>' . $array["LocationGroupName"] . '</td>';
    echo '<td>' . $array["SerialNumber"] . '</td>';
    echo '<td>' . $array["BatteryLevel"] . '</td>';
    echo '<td>' . $array["Model"] . '</td>';
    echo '<td>' . $array["LastSeen"] . '</td></tr></tbody>';
echo '</table>';
echo '</center>';
echo '<br>';
echo '<br>';
/*
echo '<center>';
echo '<h3>Usuario:</h3> <p>' . $array["UserName"] . '</p> ';
echo '<h3>Correo:</h3> <p>' . $array["UserEmailAddress"] . '</p> ';
echo '<h3>Nombre de grupo:</h3> <p>' . $array["LocationGroupName"] . '</p> ';
echo '<h3>Numero de serie:</h3> <p>' . $array["SerialNumber"] . '</p> ';
echo '<h3>Bateria: </h3><p>' . $array["BatteryLevel"] . '</p> ';
echo '<h3>Modelo: </h3><p>' . $array["Model"] . '</p> ';
echo '<h3>Ultima conexion:</h3> <p>' . $array["LastSeen"] . '</p> ';
echo '</center>';*/
print_r($array);


?>
</body>

</html>

