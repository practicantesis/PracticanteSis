<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moviles</title>
	<link rel="shortcut icon" href="img/moviles.png">
	<link rel="stylesheet" href="estilo.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&display=swap');
	</style>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');
	</style>
</head>

<script>
	function doSearch() {
		const tableReg = document.getElementById('datos');
		const searchText = document.getElementById('searchTerm').value.toLowerCase();
		let total = 0;

		// Recorremos todas las filas con contenido de la tabla
		for (let i = 1; i < tableReg.rows.length; i++) {
			// Si el td tiene la clase "noSearch" no se busca en su cntenido
			if (tableReg.rows[i].classList.contains("noSearch")) {
				continue;
			}

			let found = false;
			const cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
			// Recorremos todas las celdas
			for (let j = 0; j < cellsOfRow.length && !found; j++) {
				const compareWith = cellsOfRow[j].innerHTML.toLowerCase();
				// Buscamos el texto en el contenido de la celda
				if (searchText.length == 0 || compareWith.indexOf(searchText) > -1) {
					found = true;
					total++;
				}
			}
			if (found) {
				tableReg.rows[i].style.display = '';
			} else {
				// si no ha encontrado ninguna coincidencia, esconde la
				// fila de la tabla
				tableReg.rows[i].style.display = 'none';
			}
		}

		// mostramos las coincidencias

	}
</script>

<script>
	function abririnfo() {
		document.getElementById("flotante").style.display = "block";
	}

	function cerrarinfo() {
		document.getElementById("flotante").style.display = "none";
	}

	function enviarformulario() {
		document.formulario.submit();

	}

	function abrirdirectorio() {
		document.getElementById("directorio").style.display = "block";
	}

	function cerrardirectorio() {
		document.getElementById("directorio").style.display = "none";
	}
</script>
<!--<label for="">Buscador: <input id="searchTerm" onkeyup="doSearch()" type="text" name="buscador"></label>-->
<script type="text/javascript">
	//la funcion de busqueda ase una busqueda en la tabla y regresa los resultados
	function busquedaPorOfficina() {

		var select = document.getElementById('officinas');
		var optionSelect = select.options[select.selectedIndex]; //obtenemos las opciones que hay dentro del select

		var tabla = document.getElementById('datos'); //Obtenemos la tabla
		var Pbusqueda = optionSelect.value; //obtenemos el value que esta en las obciones de la tabla
		//Se ase un recorrido a la tabla
		for (var i = 1; i < tabla.rows.length; i++) {
			var cellsOfRow = tabla.rows[i].getElementsByTagName('td') //obtiene todos los objetos'td' de la tabla y los guarda en un array
			var found = false; //puntero

			for (var j = 0; j < cellsOfRow.length && !found; j++) {

				//si encuentra coincidencia
				if (cellsOfRow[j].innerHTML === Pbusqueda) {
					found = true;

				} //si la opcion esta vacia en la busqueda found es true para que muestre todo el recorrdio
				else if (Pbusqueda == "") {
					found = true;
				}
			}
			if (found) {
				tabla.rows[i].style.display = '';
			} else {
				tabla.rows[i].style.display = 'none';

			}
		}
	}
//	busqueda();
</script>

<body>
	<?php
	//preg_match_all('/666(.+)897/', $_SERVER['QUERY_STRING'], $matches);


	$lof = "MT1
MER
CUL
MCH
NOG
CCN
MAZ
MXL
PUE
QUE
TEP
LGT
IZT
ZAP
CHI
STA
TOL
JUA
TPZ
GDL
HLO
MEX
VIL
TIJ
PRINCIPAL
COB
MTY";

	//echo ($lasof = preg_split("/\r\n|\n|\r/", $lof));


	//print_r($lasof);
	//$nc=base64_encode($vf);
	//echo "Encoded ".base64_encode("Principal")."<br>";
	//echo "Decoded ".base64_decode($nc)."<br>";

	preg_match_all('/666(.+)897/', $_SERVER['QUERY_STRING'], $matches);
	$_SERVER['REMOTE_STRING'];


	//print_r($matches);
	$ofi = base64_decode($matches[1][0]);
	//print ($ofi);
	if (strlen($_SERVER['QUERY_STRING']) < 2) {
		echo "Bad boys, bad boys whatcha gonna do?";
		return false;
	} else {
		$oficina = "Trabajando en oficina: $ofi";
	}
	//https://ti.tpitic.com.mx/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/tabla/index.php?666Q1VM897

	?>

	<!--<a href="javascript:abririnfo()">abrir</a>-->
	<!--	<a href="http://192.168.120.179/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/airwatchdos.php">Airwatch Funcional</a> <br>
	<a href="http://192.168.120.179/INFRAESTRUCTURA-DESARROLLO/gsalazar/tabla/airwatchdos.php">Airwatch No funcional</a>  -->
	<div class="titulo">
		<div class="cabecera">
			<img class="pitic" src="img/pitic.png" alt="">
			<h1 class="dm">Dispositivos moviles <img class="movil" src="img/moviles.png" alt="celular"></h1>

		</div>
		<br>
		<div id="obsiones_officina" class="form-group oficina">
			<?php
			echo "<p>$oficina</p>";
			?>

			<?php
			if ($ofi == "Sistemas") {
			?>

				<label>Oficina:</label>

				<select id="officinas" name="oficinas" onchange="busquedaPorOfficina()">
					<option value="">Todas</option>
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

			<?php
			}
			?>

		</div>
		<label for="">Buscador: <input id="searchTerm" onkeyup="doSearch()" type="text" name="buscador"></label>

	</div>

	<div class="menu-grid">
		<?php
		if ($ofi == "Sistemas") {
			echo "<div>";
			echo "<button class='directorio' onclick='javascript:abrirdirectorio()'>Directorio</button>";
			echo "<a class='link' href='celular.php'><button class='directorio'>Celulares</button></a>";
			echo "<a class='link' href='usuarios.php'><button class='directorio'>Usuarios</button></a>";
			echo "<a class='link' href='bajas.php'><button class='directorio'>Bajas</button></a>";
			echo "</div>";
			echo "<div class='actualizar-grid'>";
			echo "<a class='link' href=''><button class='directorio'>Actualizar Tabla</button></a>";
			echo "</div>";
		}
		?>
	</div>

	<div class="ventana" id="directorio" style="display: none; height: 490px; overflow: scroll;">
		<div id="cerrar">
			<a href="javascript:cerrardirectorio()"><img src="img/cancel.png" alt="cerrar"></a>
		</div>
		<table id="datoss" class="table table-hover">
			<thead class="encabezado">
				<tr>
					<td>Oficina</td>
					<td>Enlace</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Transporte</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666VFJB897</td>
				</tr>
				<tr>
					<td>Monterrey 1</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TVQx897</td>
				</tr>
				<tr>
					<td>Merida</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TUVS897</td>
				</tr>
				<tr>
					<td>Culiacan</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666Q1VM897</td>
				</tr>
				<tr>
					<td>Mochis</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TUNI897</td>
				</tr>
				<tr>
					<td>Nogales</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666Tk9H897</td>
				</tr>
				<tr>
					<td>Cancun</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666Q0NO897</td>
				</tr>
				<tr>
					<td>mazatlan</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TUFa897</td>
				</tr>
				<tr>
					<td>Mexicali</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TVhM897</td>
				</tr>
				<tr>
					<td>Puebla</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666UFVF897</td>
				</tr>
				<tr>
					<td>Queretaro</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666UVVF897</td>
				</tr>
				<tr>
					<td>Tepic</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666VEVQ897</td>
				</tr>
				<tr>
					<td>Leon</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TEdU897</td>
				</tr>
				<tr>
					<td>Iztapalapa</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666SVpU897</td>
				</tr>
				<tr>
					<td>Zapopan</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666WkFQ897</td>
				</tr>
				<tr>
					<td>Chihuahua</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666Q0hJ897</td>
				</tr>
				<tr>
					<td>Santa ana</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666U1RB897</td>
				</tr>
				<tr>
					<td>Toluca</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666VE9M897</td>
				</tr>
				<tr>
					<td>Juarez</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666SlVB897</td>
				</tr>
				<tr>
					<td>Tepozotlan</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666VFBa897</td>
				</tr>
				<tr>
					<td>Guadalajara</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666R0RM897</td>
				</tr>
				<tr>
					<td>Hermosillo</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666SExP897</td>
				</tr>
				<tr>
					<td>Mexico</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TUVY897</td>
				</tr>
				<tr>
					<td>Villahermosa</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666VklM897</td>
				</tr>
				<tr>
					<td>Tijuana</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666VElK897</td>
				</tr>
				<tr>
					<td>Ciudad Obregon</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666Q09C897</td>
				</tr>
				<tr>
					<td>Monterrey</td>
					<td>http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666TVRZ897</td>
				</tr>
			</tbody>
		</table>

	</div>
	<center>
		<?php
		require_once('conexion.php');
		require_once('../php/funciones.php');
		$objConLDAP = new Conexion();
		$con = $objConLDAP->conectarLDAP();
		if ($con) {
			//$filter = "(duusernname=*)";duoficina
			$filter = "(duoficina=$ofi)";
			if ($ofi == "Sistemas") {
				$filter = "(duoficina=*)";
			}
			$srch = ldap_search($con, "ou=DeviceUsers,dc=transportespitic,dc=com", $filter);
			$count = ldap_count_entries($con, $srch);
			$info = ldap_get_entries($con, $srch);
			$arr = GetDevUsersFromLDAPCells("array", $info[$i]['duusernname'][0], $con);
			//print_r($arr);

			echo '<table id="datos" class="table table-hover">';
			echo '<thead class="encabezado2"><tr><th>#</th><th>Nombre</th><th>Numero de empleado</th><th>Oficina</th><th>Usuario</th><th>Telefono</th><th>Tag</th><th>Informacion</th></tr></thead>';

			for ($i = 0; $i < $count; $i++) {

				$lu = $info[$i]['duusernname'][0];
				// Condicion if para quitar aquellos registros con dispositivos de baja y que no se muestren en la tabla
				if (
					$arr[$lu]['ofi'] != 'BAJA_CEL_NOR' && $arr[$lu]['ofi'] != 'BAJA_CEL_NOR '  && $arr[$lu]['ofi'] != ' BAJA_CEL_NOR ' && $arr[$lu]['ofi'] != ' BAJA_CEL_NOR'
					&& $arr[$lu]['ofi'] != 'BAJA_CEL_NST' && $arr[$lu]['ofi'] != ' BAJA_CEL_NST' && $arr[$lu]['ofi'] != 'BAJA_CEL_NST ' && $arr[$lu]['ofi'] != ' BAJA_CEL_NST '
					&& $arr[$lu]['ofi'] != 'BAJA_CEL_SUR' && $arr[$lu]['ofi'] != ' BAJA_CEL_SUR' && $arr[$lu]['ofi'] != 'BAJA_CEL_SUR ' && $arr[$lu]['ofi'] != ' BAJA_CEL_SUR '
					&& $arr[$lu]['ofi'] != 'BAJA_CEL_OCT' && $arr[$lu]['ofi'] != ' BAJA_CEL_OCT' && $arr[$lu]['ofi'] != 'BAJA_CEL_OCT ' && $arr[$lu]['ofi'] != ' BAJA_CEL_OCT '
					&& $arr[$lu]['ofi'] != 'BAJA_CEL_CNT' && $arr[$lu]['ofi'] != ' BAJA_CEL_CNT' && $arr[$lu]['ofi'] != 'BAJA_CEL_CNT ' && $arr[$lu]['ofi'] != ' BAJA_CEL_CNT '
				) {
					$num = $num + 1;

					echo '<tbody class="tabladato"><tr><td>' . $num . '</td>';
					echo '<td>' . $info[$i]['dunombre'][0] . '</td>';
					echo '<td>' . $info[$i]['dunumeroempleado'][0] . '</td>';
					echo '<td>' . $info[$i]['duoficina'][0] . '</td>';
					//$arr[$lu]['imei'];
					//$buscarwatch = $arr[$lu]['imei'];
					//$out = QueryToAirwatchAPI("DEVICEperIMEI", $buscarwatch);
					//$array = json_decode($out, true);
					//echo '<td>' . $array["UserEmailAddress"] . '</td>';
					//	echo '<td>' . $info[$i]['duusernname'][0] . '</td>';
					echo '<td>' . $info[$i]['duusernname'][0] . '</td>';
					echo '<td>' . $arr[$lu]['num'] . '</td>';
					echo '<td>' . $arr[$lu]['tag'] . '</td>';
					



					echo '<td>
				<form id="formula' . $i . '"  method="POST">
				<input type="hidden" id=' . $i . ' name="dato" value=' . $val = $info[$i]['duusernname'][0] . '>
				<button type="button" id="mandar' . $i . '" class="boton">Ver</button>
				</form>
				<script>
			$("#mandar' . $i . '").click(function() {
				$.ajax({
					url: "ventana.php",
					type: "POST",
					data:$("#formula' . $i . '").serialize(),
					success: function(res){
						$("#flotantedos").html(res);
					}
					});
				});
				</script>
				</td></tr></tbody>';
				}
			}
			echo '</table>';
			ldap_close($con);
		}
		?>
		<div class="ventana" id="flotante">
			<div id="cerrar">
				<a href="javascript:cerrarinfo()"><img src="img/cancel.png" alt="cerrar"></a>
			</div>
			<h4>Ldap</h4>
			<div id="flotantedos">

			</div>
		</div>

	</center>
	<!--Scrip para odernar tablas--->


	<?php
	/*foreach ($lasof as &$vf) {
    //echo "Ofi: ".$vf."<br>";
    //echo 'URL: https://ti.tpitic.com.mx/INFRAESTRUCTURA-DESARROLLO/jferia/Infraestructura/tabla/index.php?666'.base64_encode($vf)."897<br>-------------------------------<br>";
}*/
	?>

	<footer>
		<p class="copyright">© 2021 Desarrollo y Mantenimiento: Juan Feria & Andres Salazar</p>
	</footer>
</body>

</html>