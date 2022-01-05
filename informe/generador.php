<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="generador.php" method="post">
    <label>Oficina:</label>

<select id="officinas" name="oficina" onchange="busquedaPorOfficina()">
    <option value="Sistemas">Sistemas</option>
    <option value="TRA">Transportes</option>
    <option value="MT1">Monterrey 1</option>
    <option value="MER">Merida</option>
    <option value="CUL">Culiacan</option>
    <option value="MCH">Mochis</option>
    <option value="NOG">Nogales</option>
    <option value="CCN">Cancun</option>
    <option value="MAZ">Mazatlan</option>
    <option value="MXL">Mexicali</option>
    <option value="PUE">Puebla</option>
    <option value="QUE">Queretaro</option>
    <option value="TEP">Tepic</option>
    <option value="LGT">Leon</option>
    <option value="IZT">Iztapalapa</option>
    <option value="ZAP">Zapopan</option>
    <option value="CHI">Chihuahua</option>
    <option value="STA">Santa ana</option>
    <option value="TOL">Toluca</option>
    <option value="JUA">Juarez</option>
    <option value="TPZ">Tepozotlan</option>
    <option value="GDL">Guadalajara</option>
    <option value="HLO">Hermosillo</option>
    <option value="MEX">Mexico</option>
    <option value="VIL">Villahermosa</option>
    <option value="TIJ">Tijuana</option>
    <option value="COB">Ciudad obregon</option>
    <option value="MTY">Monterrey</option>
</select>
        <input type="submit" value="Generar">
    </form>
    <?php
$ofi=$_POST['oficina'];
$nc=base64_encode($ofi);
$result= "http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666".$nc."897";
echo "<p>$result</p>"
    ?>
</body>

</html>