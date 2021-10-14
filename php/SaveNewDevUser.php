<?php

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";


require('funciones.php');
include('configuraciones.class.php');

$success='DUNNO';
$valid='DUNNO';
$con=ConectaLDAP();
$existnem="DUNNO";

foreach ($_POST['data'] as $value) {
	$vname="";
	//echo "<pre>";
	//print_r($value);
	//echo "</pre>";
	$vname=$value[name];
	if (preg_match("/val-(.+)/", $value[name], $out)) {
		//print_r($out);
		$vname=$out[1];
	}
	$forma[$vname]=$value[value];
}	


//: duusernname=aarcos,ou=DeviceUsers,dc=transportespitic,dc=com
$dn="duusernname=".$forma['duusernname'].",ou=DeviceUsers,dc=transportespitic,dc=com";
//$fullname=$forma['givenname']." ".$forma['sn'];
//$lastuid=GetLastLDAPUid();
$ERROR="NO";


//echo "<pre>";
//print_r($forma);
//echo "</pre>";
//return false;


$entry['duusernname']=$forma['duusernname'];
$entry['dunombre']=$forma['dunombre'];
$entry['dunumeroempleado']=$forma['dunumeroempleado'];
$entry['duoficina']=$forma['duoficina'];
$entry['objectClass'][0] = "deviceuser";


/*
echo "<pre>";
print_r($entry);
echo "</pre>";
*/





if (strlen($forma['duusernname']) < 1) {
  $ERROR="CAPTURE USUARIO!!!";
} 

if (strlen($forma['dunombre']) < 1) {
  $ERROR="CAPTURE NOMBRE Y APELLIDO!!!".strlen($forma['dunombre']);
} 

if (is_numeric($forma['dunumeroempleado'])) {
  
} else {
  $ERROR="CAPTURE NUMERO DE EMPLEADO (SOLO NUMEROS)";
}



$existnem=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","duusernname",$forma['duusernname']);
if ($existnem == "NO") {
 $ERROR="USER YA EXISTE EN DEVICEUSERS"; 
}

$existlus=CheckExistentValueLDAP("ou=DeviceUsers,dc=transportespitic,dc=com","dunumeroempleadoOriginal",$forma['dunumeroempleado']);
if ($existnem == "NO") {
 $ERROR="NO EMPLEADO YA EXISTE EN DEVICEUSERS"; 
}


/*
echo "<pre>";
print_r($entry);
echo "</pre>";
echo $ERROR;
*/

if ($ERROR == "NO") {
	$mod = ldap_add($con, $dn , $entry);
	$ERROR=ldap_error($con);
}

$jsonSearchResults[] =  array(
    'success' => $ERROR
);
echo json_encode ($jsonSearchResults);
return false;

/*

$mod = ldap_add($con, $user , $entry);


			}else{
				$json = '{"data":[';
				echo $json .= '{"error":2, "msg":"<br />Se agrego el usuario a LDAP pero NO ala tabla de cecy ni a la tabla de passwords.'.mysql_error().'"}]}';
				@mysql_close($mysql_gb);
			}
		}else{
			$json = '{"data":[';
			echo $json .= '{"error":1, "msg":"<br />Ocurrio un error al dar de alta el usuario. '.ldap_error($con).' '.$user.'"}]}';
		}
		
		ldap_close($con); 
		}




dn:uid=acota,ou=People,dc=transportespitic,dc=com
        employeeType: user
         objectClass: userinfo
                      person
                      organizationalPerson
                      inetOrgPerson
                      posixAccount
                      top
                      sambaSamAccount
                      shadowAccount
           gidNumber: 100
            accesovh: 0
           uidNumber: 11135
         preguntasec: tester
        respuestasec: tester
          maxmsgsize: 3.5
          roomNumber: SIS
             nivelhd: 4
            perfiles: 3
                      1
             infotel: 9608
           MobileDev: 84:51:81:EB:A4:48
            sambaSID: S-1-5-21-2286529612-1239631486-3098793819-1002
sambaPasswordHistory: 0000000000000000000000000000000000000000000000000000000000000000
      sambaAcctFlags: [U          ]
    shadowLastChange: 16207
     sambaPwdLastSet: 1400916704
          loginShell: /bin/basho
            pushover: ugMP8vC5VmrTEZQMRFPqBHjHvc1Pxj
          travelling: YES
         livemeeting: NO
        feriacustom1: 3
           nivelscup: 1
             deptorh: 3
             nivelrh: C
               lanip: 192.168.140.143
              lanmac: 54:E1:AD:61:AA:15
              wifiip: 192.168.222.179
             wifimac: AC:ED:5C:72:85:30
            voicemac: 24:D9:21:48:85:CA
          voicemodel: 9608
           extension: 11615
             voiceip: 10.150.125.110
       claveTelefono: NO
              urlvip: 0
       homeDirectory: /home/sincorreo
              correo: 0
                 web: 1
              jabber: 1
          UsoMoviles: SI
              Office: 0
           anfitrion: 1
           servicios: NO
            cobrador: 0
              chofer: 0
   accesostemporales: NO
              drupal: 1
                 uid: acota
                  cn: Alex Cota
           givenName: Alex
                  sn: Cota
                mail: acota@tpitic.com.mx
              puesto: no establecido
             oficina: SIS
          noempleado: 1839
         aliasgoogle: user1
        accesosdered: 8
      licenciagoogle: Business
   aliascuentagoogle: acota@transportespitic.com.mx
                      acota@transportespitic.com
                      acota@transportespitic.mx
                      acota@tpitic.com
                      acota@tpitic.mx
                      admin@tractoremolques.com.mx
        userPassword: {SHA}VPT0Iu2+YKTi0IN5jHGgCGeL+3A=
     sambaNTPassword: 96693806C8B181C36154E630C835A62F

    $values[$vname][0] = array();
    $values[$vname][0]=$value;
    //print_r($values);
    //ldap_modify($ldapBind, $dn, $values);
    if (@ldap_mod_del($ldapBind, $dn, $values)) {
        $RES="YES";
    } else {
         $RES=ldap_error($ldapBind);
    }
    return $RES;
}

*/




?>


