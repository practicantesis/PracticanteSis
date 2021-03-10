<?php
require('funciones.php');
include('configuraciones.class.php');
//print_r($_POST);
$success='DUNNO';
$valid='DUNNO';

$conn = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $fwdb);
//echo $conn=ConectaSQL();

$pila = array();


$sql = "SELECT abrev from oficinas";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($pila, $row["abrev"]);
    }
} else {
    echo "0 results";
}
array_push($pila, "DAF");
array_push($pila, "SIS");
array_push($pila, "RH");
array_push($pila, "DC");
array_push($pila, "DFA");
array_push($pila, "DG");
array_push($pila, "DO");
array_push($pila, "TD");

//print_r($pila);

if ($_POST['value'] == "oficina") {
	if (in_array($_POST['nvalue'], $pila)) {
    	//echo "OFI OK";
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="ACABAS DE INVENTAR UNA NUEVA OFICINA ".$_POST['nvalue'].", FELICIDADES!";
	}
}


if ($_POST['value'] == "aliascuentagoogle") {
	if ((filter_var($_POST['nvalue'] , FILTER_VALIDATE_EMAIL)) or ($_POST['nvalue'] == "NO")) {
	    //echo("$ip is a valid IP address");
	    $valid="YES";
	} else {
	    $success=$_POST['nvalue']."No es una direccion de correo valida";
	}
}


if  (($_POST['value'] == "extension") or ($_POST['value'] == "tag")) {
	if (preg_match("/^(?:NO|\d+)$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="El valor es numerico o 'NO'...";
	}
}


if ($_POST['value'] == "noempleado") {
	if (preg_match("/^\d+$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="El numero de empleado es numerico...";
	}
}

if ($_POST['value'] == "puesto") {
	if (preg_match("/^\w+$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="El puesto es una sola palabra...";
	}
}

if (($_POST['value'] == "givenname") or ($_POST['value'] == "sn")) {
	if (preg_match("/^[a-zA-Z\s]+$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="El valor debe ser solo letras (y espacios) sin caracteres especiales...";
	}
}

if ($_POST['value'] == "travelling") {
	if (preg_match("/^(?:YES|NO)$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="Valores aceptados: YES | NO";
	}
}

if ($_POST['value'] == "accesosdered") {
	if (preg_match("/^[1|2|3|4|5|6|7|8]$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="Valores aceptados: 1|2|3|4|5|6|7|8";
	}
}

if ($_POST['value'] == "nivelscup") {
	if (preg_match("/^[1|2|3]$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="Valores aceptados: 1|2|3";
	}
}

if ($_POST['value'] == "aliasgoogle") {
	if (preg_match("/^(?:user1|gtes|volvo|NO)$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="Valores aceptados: user1|gtes|volvo|NO";
	}
}


if ($_POST['value'] == "anfitrion") {
	if (preg_match("/^[1|2|3]$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="Valores aceptados: 1|2|3";
	}
}

if ($_POST['value'] == "licenciagoogle") {
	if (preg_match("/^(?:Basic|Business|Lite|NO)$/i",$_POST['nvalue'])) {
    	$valid="YES";
	} else {
		//echo "OFI NOT OK";
		$success="Valores aceptados: Basic|Business|Lite|NO";
	}
}

if ($_POST['value'] == "lanip") {
	if ((filter_var($_POST['nvalue'] , FILTER_VALIDATE_IP)) or ($_POST['nvalue'] == "NO")) {
	    //echo("$ip is a valid IP address");
	    $valid="YES";
	} else {
	    $success=$_POST['nvalue']."No es una IP address valida";
	}
}

if ($_POST['value'] == "lanmac") {
	$_POST['nvalue']=strtoupper($_POST['nvalue']);
	$exma=VerifyMAC($_POST['nvalue']);
	if ((filter_var($_POST['nvalue'] , FILTER_VALIDATE_MAC)) or ($_POST['nvalue'] == "NO")) {
	    //echo("$ip is a valid IP address");
	    $valid="YES";
	} else {
	    $success=$_POST['nvalue']."No es una MAC address valida";
	}

	if ( ($exma == "EXISTE") and ($_POST['nvalue'] != "NO") ) {
		$valid='NO';
		$success=$_POST['nvalue']." MAC address existente en LDAP";
	}


//return false;
}


if ($_POST['value'] == "wifimac") {
	$_POST['nvalue']=strtoupper($_POST['nvalue']);
	$exma=VerifyMAC($_POST['nvalue']);
	if ((filter_var($_POST['nvalue'] , FILTER_VALIDATE_MAC)) or ($_POST['nvalue'] == "NO")) {
	    //echo("$ip is a valid IP address");
	    $valid="YES";
	} else {
	    $success=$_POST['nvalue']."No es una MAC address valida";
	}

	//if ($exma == "EXISTE") {
	if ( ($exma == "EXISTE") and ($_POST['nvalue'] != "NO") ) {		
		$valid='NO';
		$success=$_POST['nvalue']." MAC address existente en LDAP";
	}


//return false;
}




if ($_POST['value'] == "mail") {
	$success="Valor NO editable!";
}


if ($_POST['value'] == "SAMBA") {
	$hassmb=CheckSMBServiceForUser($_POST['dn']);
	$success= "Activando Samba para ".$_POST['dn']." dice que ".$_POST['value']." y ".$_POST['nvalue']." has samba is ".$hassmb;
	if ($_POST['nvalue'] == $hassmb) {
		$success="Es el mismo valor que tiene el usuario";
	} else {
		$con=ConectaLDAP();
		if ($_POST['nvalue'] == "SIx") {
			$sambaArray = array();
			$sambaArray['objectClass'][0] = "sambaSamAccount";
			$sambaArray['sambaSID'] = "S-1-5-21-2286529612-1239631486-3098793819-1002";
			$sambaArray['objectClass'][1] = "shadowAccount";
			$sambaArray['sambaPasswordHistory'] = "0000000000000000000000000000000000000000000000000000000000000000";
			$sambaArray['sambaAcctFlags'] = "[U          ]";
			$sambaArray['shadowLastChange'] = "16207";
			$sambaArray['sambaPwdLastSet'] = "1400916704";
			$smbReq = @ldap_mod_add($con,$_POST['dn'],$sambaArray);
			$success="Samba activado";
		}
		if ($_POST['nvalue'] == "NxxO") {
			$userdataModifydelSamba = array();
			$userdataModifydelSamba['objectClass'] = array();
			$userdataModifydelSamba['objectClass'][6] = 'sambaSamAccount';
			//$userdataModifydelSamba['objectClass'][1] = 'shadowAccount';
			$userdataModifydelSamba['sambaSID'] = array();
			$userdataModifydelSamba['sambaPwdLastSet'] = array();
			$userdataModifydelSamba['sambaNTPassword'] = array();
			$userdataModifydelSamba['sambaPasswordHistory'] = array();
			$userdataModifydelSamba['sambaAcctFlags'] = array();
			$userdataModifydelSamba['shadowLastChange'] = array();
			$sucess = ldap_mod_del($con, $_POST['dn'], $userdataModifydelSamba);


        echo "<pre>";
        echo $_POST['dn'];
        print_r($sambaArray);
        echo "</pre>";

			$smbReq = ldap_mod_del($con,$_POST['dn'], $sambaArray);
			$success="Samba eliminado";
		}
		if(!$smbReq){
			$success="Ocurrio error al grabar en LDAP:".ldap_error($con);
		}
	}
	$valid='NO';	
}



if ($valid=="YES") {
	if ($_POST['value'] == "aliascuentagoogle") {
		$success=AppendLDAPVAl($_POST['dn'],$_POST['nvalue'],$_POST['value']);
	} else {
		$success=UpdateLDAPVAl($_POST['dn'],$_POST['nvalue'],$_POST['value']);	
	}
	
	//UpdateDelFeria($_POST['dn'],$_POST['nvalue'],$_POST['value']);
}




$jsonSearchResults[] =  array(
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;
?>
