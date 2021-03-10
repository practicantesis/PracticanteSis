<?php
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
return false;
*/
require('funciones.php');
include('configuraciones.class.php');

$success='DUNNO';
$valid='DUNNO';
$con=ConectaLDAP();

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

$dn="uid=".$forma['uid'].",".$LDAPUserBase;
$fullname=$forma['givenname']." ".$forma['sn'];
$lastuid=GetLastLDAPUid();
$ERROR="NO";





/*

echo "<pre>";
print_r($forma);
echo "</pre>";
return false;

*/



$entry['uid']=$forma['uid'];
$entry['cn']=$fullname;
$entry['employeeType']="user";
$entry['roomNumber']=$forma['oficina'];
$entry['givenName']=$forma['givenname'];
$entry['sn']= $forma['sn'];
$entry['mail']=$forma['mail'];
$entry['loginShell']="/bin/false";
$entry['uidNumber']=($lastuid+1);
$entry['gidNumber']="100";
$entry['oficina']=$forma['oficina'];
$entry['noempleado'] = $forma['noempleado'];
$entry['deptorh']="NO";
$entry['accesovh']="NO";
$entry['objectClass'][0] = "top";
$entry['objectClass'][1] = "posixAccount";
$entry['objectClass'][2] = "inetOrgPerson";
$entry['objectClass'][3] = "organizationalPerson";
$entry['objectClass'][4] = "person";
$entry['objectClass'][5] = "userinfo";
//$entry['aliasdecorreo'][0] = "unset";
$entry['livemeeting'] = "NO";
if ( strlen($forma['puesto']) < 1) {
	$ERROR="ERROR PUESTO";
} else {
	$entry['puesto'] = strtolower($forma['puesto']);
}

if ($forma['samba'] == 1 ) {
  $entry['objectClass'][6] = 'sambaSamAccount';
  $entry['objectClass'][7] = 'shadowAccount';
  $entry['sambaSID'] = "S-1-5-21-2286529612-1239631486-3098793819-1002";
  $entry['sambaPasswordHistory'] = "0000000000000000000000000000000000000000000000000000000000000000";
  $entry['sambaAcctFlags'] = "[U          ]";
  $entry['shadowLastChange'] = "16207";
  $entry['sambaPwdLastSet'] = "1400916704";
}


/*
echo "<pre>";
print_r($entry);
echo "</pre>";
echo $forma['samba'];
return false;
*/


// LANIP
if (filter_var($forma['lanip'], FILTER_VALIDATE_IP)) {
	$entry['lanip'] = $forma['lanip'];
} else {
	$SUCCESS="NO";
}
// LANMAC
if ($forma['lanmac'] == "Definido con valor OCS") {
	if (filter_var($forma['lanmac-sel'], FILTER_VALIDATE_MAC)) {
		$entry['lanmac'] = $forma['lanmac-sel'];
	}
} else {
	if (filter_var($forma['lanmac'], FILTER_VALIDATE_MAC)) {
		$entry['lanmac'] = $forma['lanmac'];
	}
}
if ($forma['lanmac'] == "NO APLICA") {
	$entry['lanmac'] = "NO";
}	
// WIFIMAC
if ($forma['wifimac'] == "Definido con valor OCS") {
	if (filter_var($forma['wifimac-sel'], FILTER_VALIDATE_MAC)) {
		$entry['wifimac'] = $forma['wifimac-sel'];
	}
} else {
	if (filter_var($forma['wifimac'], FILTER_VALIDATE_MAC)) {
		$entry['wifimac'] = $forma['wifimac'];
	}
}


if ($forma['wifimac'] == "NO APLICA") {
	$entry['wifimac'] = "NO";
}	

$entry['accesostemporales'] = "NO";

// MAIL
if ($forma['EmailService'] == "SELECCIONE") {
	$ERROR="EMAIL ERROR";
} 
if ($forma['EmailService'] == "SI") {
	//$entry['EmailService']= $forma['EmailService'];
	$entry['aliasgoogle'] = "user1";
}
if ($forma['EmailService'] == "NO") {
	//$entry['EmailService']= $forma['EmailService'];
	$entry['aliasgoogle'] = "NO";
}
$entry['correo'] = 1;
$entry['homeDirectory']="/home/directorynotset";
$entry['aliasdecorreo'][0] = $forma['oficina'];


// DRUPAL
if ($forma['Drupal'] == "SELECCIONE") {
	$ERROR="ERROR DRUPAL";
} 
if ($forma['Drupal'] == "SI") {
	$entry['servicios']= "DRUPAL";
}
if ($forma['Drupal'] == "NO") {
	$entry['servicios']= "NO";
}
$entry['perfiles'] = 0;
$entry['feriacustom1']=0;
$entry['nivelscup']=0;
$entry['accesosdered'] = $forma['accesosdered'];
$entry['jabber'] = "0";


/*
echo "<pre>";
print_r($entry);
echo "</pre>";
echo $ERROR;
*/



if ($ERROR == "NO") {
  $MAMBOERR=SaveMamboUser($entry);
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


