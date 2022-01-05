<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
	<title>Document</title>
</head>

<body>
	<center>
		<?php
		require_once('conexion.php');

		$objConLDAP = new Conexion();
		$con = $objConLDAP->conectarLDAP();
		$buscar = $_POST['dato'];
		$buscar2 = str_replace($buscar, "", "");
		if ($con) {
			$filter = "(DeviceAssignedTo=*$buscar*)";
			$srch = ldap_search($con, "ou=Celulares,ou=Devices,dc=transportespitic,dc=com", $filter);
			$count = ldap_count_entries($con, $srch);
			$info = ldap_get_entries($con, $srch);

			echo '<table id="datos" class="table table-hover">';
			echo '<thead class="encabezado"><tr><td>TAG</td><td>Departamento</td><td>Oficina</td><td>Marca</td><td>IMEI</td><td>Usuario</td></tr></thead>';

			for ($i = 0; $i < $count; $i++) {
				echo '<tbody><tr><td>' . $info[$i]['devicetag'][0] . '</td>';
				echo '<td>' . $info[$i]['devicedept'][0] . '</td>';
				echo '<td>' . $info[$i]['deviceoffice'][0] . '</td>';
				echo '<td>' . $info[$i]['devicebrand'][0] . '</td>';
				echo '<td>' . $info[$i]['deviceimei'][0] . '</td>';
				echo '<td>' . $info[$i]['deviceassignedto'][0] . '</td></tr></tbody>';
				//echo '<td>' . $info[$i]['deviceserial'][0] . '</td>;

				//echo '<td><button onclick=$info[$i]['deviceassignedto'][0]>mostrar</button></td></tr></tbody>';

				echo '</table>';
				
				require('php/funciones.php');
				$buscarwatch = $info[$i]['deviceserial'][0];


				$out = QueryToAirwatchAPI("DEVICE", $buscarwatch); //MXRNU19802100085
				$array = json_decode($out, true);
				//print_r($out);
				echo '<h4>Airwatch</h4>';
				echo '<table class="table table-hover">';
				echo '<thead " class="encabezado"><tr><td>Usuario</td><td>Correo</td><td>Nom. de grupo</td><td>Num. serie</td><td>Modelo</td></tr></thead>';
				echo '<tbody><tr><td>' . $array["UserName"] . '</td>';
				echo '<td>' . $array["UserEmailAddress"] . '</td>';
				echo '<td>' . $array["LocationGroupName"] . '</td>';
				echo '<td>' . $array["SerialNumber"] . '</td>';
				echo '<td>' . $array["Model"] . '</td></tr></tbody>';
				echo '</table>';

				echo '<table class="table table-hover">';
				echo '<thead class="encabezado"><tr><td>Numero activo</td><td>Nombre descriptivo del dispositivo</td><td>MAC</td><td>Ultima vez visto</td><td>Ultima inscripcion</td></tr></thead>';
				echo '<tbody><tr><td>' . $array["AssetNumber"] . '</td>';
				echo '<td>' . $array["DeviceFriendlyName"] . '</td>';
				echo '<td>' . $array["MacAddress"] . '</td>';
				echo '<td>' . $array["LastSeen"] . '</td>';
				echo '<td>' . $array["LastEnrolledOn"] . '</td></tr></tbody>';
				echo '</table>';

				ldap_close($con);
			}
		}


		echo '<script>
			abririnfo();
			</script>';
		?>
	</center>
</body>

</html>