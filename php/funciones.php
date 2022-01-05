<?php


function GetBrandFromModel($model) {
    switch ($model) {
    case (preg_match('/HUAWEI/', $model) ? true : false) :
        $brand="Huawei";
        break;
    case (preg_match('/ZTE/', $model) ? true : false) :
        $brand="ZTE";
        break;

    case 1:
        echo "i equals 1";
        break;
    case 2:
        echo "i equals 2";
        break;
    }
    return $brand;
}

function QueryToAirwatchAPI($tipo,$val) {
    $basic_auth = base64_encode("jferia:TP1nghm0R1hM0yz");
    //$basic_auth='amZlcmlhOkxldHR5b3J0ZWdh';
    $ch = curl_init();
    $api_key='Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=';
    $baseurl="https://as257.awmdm.com";
    if ($tipo == "ALLDEVS") {
        $endpoint="/API/mdm/devices/search";    
    }
    if ($tipo == "DEVICE") {
        $endpoint="/api/mdm/devices/?searchby=Serialnumber&id=".$val;
    }
    if ($tipo == "DEVICEperIMEI") {
        $endpoint="/api/mdm/devices/?searchby=ImeiNumber&id=".$val;
    }
    $url = $baseurl.$endpoint;
    $headers = ['aw-tenant-code: '.$api_key,'Authorization: Basic '.$basic_auth,'Accept: application/json'];
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    $ch_result = curl_exec($ch);
    $infos = curl_getinfo($ch);
    //If http_code is not 200, then there's an error
    if ($infos['http_code'] != 200) {
        $result['status'] = AIRWATCH_API_RESULT_ERROR;
        $result['error']  = $infos['http_code'];
    } else {
        $result['status'] = AIRWATCH_API_RESULT_OK;
        $result['data'] = $ch_result;
    }
    //print_r($result);
    //echo $tipo,$val;
    curl_close($ch);
    if ($result['status'] == "AIRWATCH_API_RESULT_ERROR") {
        return $result['error'];
    } else {
        return $result['data'];    
    }
    
}



function SaveMamboUser($datos) {
    //print_r($datos);
    $conn=ConectaSQL('globaldb');
    $nom = $datos['cn'].' '.$datos['givenName'];
    $sql="SELECT COUNT(*) AS entradas FROM only_users WHERE username = '".$datos["uid"]."'";
    //$passAleatorio='tpitic';
    $passAleatorio=$datos["userPassword"];
    $q = "INSERT INTO only_users VALUES(NULL,'".$nom."','".$datos['uid']."','".$datos['uid']."@transportespitic.com','".md5($passAleatorio)."','Registered',0,1,18,NOW(),'','','',null,'1','".$datos['oficina']."',null,'0','0')";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        //echo $row["entradas"];
        if ($row["entradas"] == 0) {
            //print_r($row);
            $qr = $conn->query($q);
            if ($qr) {
                return "OK";    
            } else {
                return "FAIL";
                //echo $row["entradas"]." <-- entradas";
            }
        } else {
            return "FAIL";
            //echo $row["entradas"]." <-- entradas";
        }
    }
    //echo "xxxxxxxxx";
    //return false;
}

function VerifyMAC($mac) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,$LDAPUserBase, "(|(lanmac=$mac)(wifimac=$mac))");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    if ( $ldata["count"] > 0) {
        $out = "EXISTE";
    } else {
        $out = "NOEXISTE";
    }

    return $out;
}


function ConectaSQL($base) {
    include 'configuraciones.class.php';
    global $conn;
    if ($base == "ocsweb") {
        $mysqlhost="mysqlo";
        $mysqluser="ocs";
        $mysqlpass="#sistemaspitiC#123";
    }
    if ($base == "globaldb") {
        $mysqlhost="dbmysql.transportespitic.com";
        $mysqluser="adminusertpitic";
        $mysqlpass="adminusertpitic";
    }
    $conn = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $base);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        echo $conn->connect_error;
    }
    return $conn;
}    

function ConectaLDAP() {
    include 'configuraciones.class.php';
    $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
    }
    $err=ldap_error($ldapconn);
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0);
    return $ldapconn;

}

function GetUsersFromLDAP($base,$how) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
    }
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,$LDAPUserBase, "(uid=*)");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo $ldata["count"];
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        if ($how == "htmltable") {
            $out .= "<tr><td>".$ldata[$i]['uid'][0]."</td></tr>";    
        }
        if ($how == "array") {
            array_push($out,$ldata[$i]['uid'][0]);
        }
        if ($how == "exists") {
            return $ldata["count"];
        }
        
        //echo "</pre>";
        //return false;
    }
    //echo $out;
    return $out;
}




function GetDevUsersFromLDAPCells($how,$ofi,$conn) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
    }
    $ldapconn=$conn;
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,"ou=Celulares,ou=Devices,dc=transportespitic,dc=com", "(deviceoffice=*)");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo $ldata["count"];
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        if ($how == "htmltable") {
            $out .= "<tr><td>".$ldata[$i]['uid'][0]."</td></tr>";    
        }
        if ($how == "array") {
            $at=$ldata[$i]['deviceassignedto'][0];
            //$out[$at]=$ldata[$i]['devicetag'][0];
            $out[$at]['tag']=$ldata[$i]['devicetag'][0];
            $out[$at]['num']=$ldata[$i]['devicenumber'][0];
            $out[$at]['ofi']=$ldata[$i]['deviceoffice'][0];
            $out[$at]['imei']=$ldata[$i]['deviceimei'][0];

            //array_push($out,$ldata[$i]['uid'][0]);
        }
        if ($how == "exists") {
            return $ldata["count"];
        }
        
        //echo "</pre>";
        //return false;
    }
    //echo $out;
    return $out;
}







function GetNoEmpInfoFromLDAP($user,$how) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
    }
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,$LDAPUserBase, "(noempleado=$user)");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo $ldata["count"];
    //print_r($ldata);
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        if ($how == "htmltable") {
            $out .= "<tr><td>".$ldata[$i]['uid'][0]."</td></tr>";    
        }
        if ($how == "array") {
            $out['uid']=$ldata[$i]['uid'][0];
            $out['cn']=$ldata[$i]['cn'][0];
            $out['oficina']=$ldata[$i]['oficina'][0];
        }
        
        //echo "</pre>";
        //return false;
    }
    //print_r($out);
    return $out;
}

function GetUserInfoFromLDAP($user,$how) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
    }
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,$LDAPUserBase, "(uid=$user)");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo $ldata["count"];
    //print_r($ldata);
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        if ($how == "htmltable") {
            $out .= "<tr><td>".$ldata[$i]['uid'][0]."</td></tr>";    
        }
        if ($how == "array") {
            $out['uid']=$ldata[$i]['uid'][0];
            $out['cn']=$ldata[$i]['cn'][0];
            $out['oficina']=$ldata[$i]['oficina'][0];
        }
        //echo "</pre>";
        //return false;
    }
    //print_r($out);
    if ($how == "exists") {
        $out = $ldata["count"];    
    }


    return $out;
}


function GetGroupsForUser($user,$how) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
    }
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,"ou=TPiticGoogleAliases,ou=groups,dc=transportespitic,dc=com", "(member=uid=".$user.",ou=People,dc=transportespitic,dc=com)");
    //$result = ldap_search($ldapconn,$LDAPGroupBase, "(member=uid=".$user.",ou=People,dc=transportespitic,dc=com)");
    //"ou=TPiticGoogleAliases,ou=groups,dc=transportespitic,dc=com"
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo $ldata["count"];
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        //echo "</pre>";
        if ($how == "htmltable") {
            $out .= "<tr><td>".$ldata[$i]['dn']."</td></tr>";    
        }
        if ($how == "array") {
            array_push($out,$ldata[$i]['dn']);
        }
        
        
        //return false;
    }
    //echo $out;
    return $out;
}


function GetGroupMembers($user,$how,$what) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
    }
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    
    $result = ldap_search($ldapconn,"ou=TPiticGoogleAliases,ou=groups,dc=transportespitic,dc=com", "(cn=".$user.")");
    //$result = ldap_search($ldapconn,$LDAPGroupBase, "(cn=".$user.")");
    //echo $user;
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo $ldata["count"];
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        //echo "</pre>";

        foreach ($ldata as &$valor) {
            //print_r($valor);

        }

        if ($what == "groupname") {
            if ($how == "htmltable") {
                $out .= "<tr><td>".$ldata[$i]['dn']."</td></tr>";    
            }
            if ($how == "array") {
                array_push($out,$ldata[$i]['dn']);
            }
        }
        if ($what == "groupmembers") {
            if ($how == "htmltable") {
                $out .= "<tr><td>".$ldata[$i]['dn']."</td></tr>";    
            }
            if ($how == "array") {
                $count=$ldata[$i]['member']['count'];
                for ($x = 0; $x < $count; $x++) {
                    //echo "The number is: $x <br>";
                    array_push($out,$ldata[$i]['member'][$x]);
                }
               
                
            }
        }
        
        //return false;
    }
    //echo $out;
    return $out;
}

function smbGetGroupMembers($user,$how,$what) {
    //echo "ENTRE";
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        //$out=array();
    }
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    $result = ldap_search($ldapconn,"ou=Samba,ou=groups,dc=transportespitic,dc=com", "(cn=".$user.")");
    //echo "xxxx  $user xxxxx";
    //$result = ldap_search($ldapconn,$LDAPGroupBase, "(cn=".$user.")");
    //echo $user;
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //$out .= "xx ".$ldata["count"]." xx";
    //print_r($ldata);

    if ($what == "smbgroupmembers") {
        foreach ($ldata as &$valor) {
            foreach ($valor[memberuid] as &$valorb) {
                //echo $valorb;
                if (is_array($valorb)) {

                } else {
                    if ( strlen($valorb) > 2 ) {
                      $out .='<button type="button" id="del#'.$valorb.'#'.$user.'" class="btn btn-primary btn-sm" onclick="SmbGrpMemberAction('."'$valorb'".','."'$user'".','."'delete'".')" >Borrar</button> '.$valorb.'<br>';  
                    }
                }
                //if ( strlen($valorb) > 2 ) {
                //  $out .='<button type="button" id="del#'.$valorb.'#'.$user.'" class="btn btn-primary btn-sm">Borrar</button> '.$valorb.'<br>';  
                //}
            }
            //print_r($valor[memberuid]);
        }
        $out .='<button type="button" id="add#newmwmbwer#'.$user.'" class="btn btn-primary btn-sm" onclick="SmbGrpMemberAction('."'$user'".','."'$user'".','."'add'".')">Nuevo </button><INPUT TYPE="text" id="newsmbgrpuser" name="newsmbgrpuser" size=9><br>';
    }
    //echo $out;
/*
    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        //echo "</pre>";
        if ($what == "groupncame") {
            if ($how == "htmltable") {
                $out .= "<tr><td>".$ldata[$i]['dn']."</td></tr>";    
            }
            if ($how == "array") {
                array_push($out,$ldata[$i]['dn']);
            }
        }
        if ($what == "groupmcembers") {
            if ($how == "htmltable") {
                $out .= "<tr><td>".$ldata[$i]['dn']."</td></tr>";    
            }
            if ($how == "array") {
                $count=$ldata[$i]['member']['count'];
                for ($x = 0; $x < $count; $x++) {
                    //echo "The number is: $x <br>";
                    array_push($out,$ldata[$i]['member'][$x]);
                }
               
                
            }
        }
        
        //return false;
    }
    */
    //echo $out;
    $outb="<strong><big>Miembros del Grupo: </big></strong><br>".$out;
    return $outb;
}


function GetDeviceListFromLDAP($base,$what) {
    $err='';
	$data='';
	set_time_limit(30);
	$ldapserver = 'ldap.tpitic.com.mx';
	$ldapuser      = 'cn=feria,dc=transportespitic,dc=com';  
	$ldappass     = 'sistemaspitic';
	$ldaptree    = $base;
    $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
    $array1= array();
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
        if ($ldapbind) {
            //$result = ldap_search($ldapconn,$ldaptree, "($what=*)") or die ("Error in search query: ".ldap_error($ldapconn));
            // Buscar solo deice sin ofic. BAJA
            if ($what == "activetag" ) {
                $result = ldap_search($ldapconn,$ldaptree, "(!(deviceoffice=BAJA_*))") or die ("Error in search query: ".ldap_error($ldapconn));
                $what="devicetag";
            } else {
                $result = ldap_search($ldapconn,$ldaptree, "($what=*)") or die ("Error in search query: ".ldap_error($ldapconn));
            }
            $ldata = ldap_get_entries($ldapconn, $result);
            for ($i=0; $i<$ldata["count"]; $i++) {
                array_push($array1,$ldata[$i][$what][0]);
                //echo "<pre>";
                //print_r($ldata);
                //echo "XXX".$ldata[$i][$what][0];
                //echo "</pre>";
                //return false;
            }
        }
    }
    return $array1;
}

function GetFilteredDeviceListFromLDAP($base,$what,$value,$whatreturn) {
    $err='';
    $data='';
    set_time_limit(30);
    $ldapserver = 'ldap.tpitic.com.mx';
    $ldapuser      = 'cn=feria,dc=transportespitic,dc=com';  
    $ldappass     = 'sistemaspitic';
    $ldaptree    = $base;
    $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
    $array1= array();
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
        if ($ldapbind) {
            $result = ldap_search($ldapconn,$ldaptree, "($what=$value)") or die ("Error in search query: ".ldap_error($ldapconn));
            $ldata = ldap_get_entries($ldapconn, $result);
            for ($i=0; $i<$ldata["count"]; $i++) {
                array_push($array1,$ldata[$i][$whatreturn][0]);
                //echo "<pre>";
                //print_r($ldata);
                //echo "XXX".$ldata[$i][$what][0];
                //echo "</pre>";
                //return false;
            }
        }
    }
    return $array1;
}

function GetDeviceInfoFromLDAP($base,$what,$tag) {
    $err='';
    $data='';
    set_time_limit(30);
    $ldapserver = 'ldap.tpitic.com.mx';
    $ldapuser   = 'cn=feria,dc=transportespitic,dc=com';  
    $ldappass   = 'sistemaspitic';
    $ldaptree   = $base;
    $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
    $array1= array();
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
        if ($ldapbind) {
            $result = ldap_search($ldapconn,$ldaptree, "($what=$tag)") or die ("Error in search query: ".ldap_error($ldapconn));
            $ldata = ldap_get_entries($ldapconn, $result);
            
            //for ($i=0; $i<$ldata["count"]; $i++) {
                //array_push($array1,$ldata[$i][$what][0]);
                //echo "<pre>";
                //print_r($ldata);
                //echo "XXX".$ldata[$i][$what][0];
                //echo "</pre>";
                //return false;
            //}
        }
    }
    return $ldata;
}

function GetDeviceUserInfoFromLDAP($user) {
    $err='';
    $data='';
    set_time_limit(30);
    $ldapserver = 'ldap.tpitic.com.mx';
    $ldapuser   = 'cn=feria,dc=transportespitic,dc=com';  
    $ldappass   = 'sistemaspitic';
    $ldaptree   = "ou=DeviceUsers,dc=transportespitic,dc=com";
    $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
    $array1= array();
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
        if ($ldapbind) {
            $result = ldap_search($ldapconn,$ldaptree, "(duusernname=$user)") or die ("Error in search query: ".ldap_error($ldapconn));
            $ldata = ldap_get_entries($ldapconn, $result);
        }
    }
    return $ldata;
}


function GetDeviceTagInfoFromAssignedUserLDAP($user) {
    $err='';
    $data='';
    set_time_limit(30);
    $ldapserver = 'ldap.tpitic.com.mx';
    $ldapuser   = 'cn=feria,dc=transportespitic,dc=com';  
    $ldappass   = 'sistemaspitic';
    $ldaptree   = "ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
    $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
    $array1= array();
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
        if ($ldapbind) {
            $result = ldap_search($ldapconn,$ldaptree, "(deviceassignedto=$user)") or die ("Error in search query: ".ldap_error($ldapconn));
            $ldata = ldap_get_entries($ldapconn, $result);
        }
    }
    return $ldata;
}



function ValidatePassword($password, $hash) {
        $algoritmo = substr($hash, 1, 3);
        if($algoritmo == 'SSH'){
                $hash = base64_decode(substr($hash, 6));
                $original_hash = substr($hash, 0, 20);
                $salt = substr($hash, 20);
                $new_hash = mhash(MHASH_SHA1, $password . $salt);
                if(strcmp($original_hash, $new_hash) == 0){
                        $status="SI";
                }else{
                        $status="NO";
                }

        }else{
                $newPass = "{SHA}".base64_encode(sha1($password, TRUE));
                if(strcmp($hash, $newPass) == 0){
                        $status = "SI";
                }else{
                        $status = "NO";
                }
        }
        return $status;
        //return false;
}


function GetRegionalesFromOficinas() {
    //$conn=ConectaMySQL("firewall");
    global $conn;
    $sql = "select DISTINCT regional from oficinas where regional != 'UNDEFINED' ";
    $result = $conn->query($sql);
    $pila = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($pila, $row["regional"]);
            //echo $row["regional"]."sss".$result->num_rows."ggg<br>";
        }
    }
    return $pila;
}


function CheckMultipleNets($ofi) {
    $conn=ConectaSQL('firewall');
    //global $conn;
    $sql = "select multiplenet from oficinas where abrev = '$ofi' ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $pila = $row["multiplenet"];
            //echo $row["regional"]."sss".$result->num_rows."ggg<br>";
        }
    }
    return $pila;
}


function GetRegionaleFromOficina($ofi,$db) {
    //$conn=ConectaSQL("firewall");
    global $conn;
    //$conn=$db;
    $sql = "select regional from oficinas where abrev = '$ofi' ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row["regional"];
            
        }
    }
    //return $pila;
}


function GetHardwareIDFromUsername($user) {
    //echo "<pre>";
    //print_r($GLOBALS);
    //echo "</pre>";
    //$conn=$db;
    error_reporting(1);
    global $conn;

    $res_type = is_resource($conn) ? get_resource_type($conn) : gettype($conn);
    if(strpos($res_type, 'mysql') === false) {
        $conn = ConectaSQL('ocsweb');
    }       
    //error_reporting(E_ALL);
    $sql = "select HARDWARE_ID from accountinfo where tag='".$user."'";
    $result = $conn->query($sql);
    //echo $conn->connect_error);
    //echo $result->num_rows;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ret = $row["HARDWARE_ID"];
            
        }
    } else {
        $ret="NO";
    }
    //return $pila;
    return $ret;
}

function GetOfficeFromOCSHWID($hwid,$conn) {
    $out=Array();
    $sql = "select fields_3,TAG from accountinfo where HARDWARE_ID='".$hwid."'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            //$ret = $row["hwid"];
            //print "TAG: ".$row["TAG"]."<br>";
            $out['TAG']=$row["TAG"];
            $sqlb = "select TVALUE from config where IVALUE='".$row["fields_3"]."'";
            $resultb = $conn->query($sqlb);
            while($rowb = $resultb->fetch_assoc()) {
                //print $rowb["TVALUE"]." <-- Oficina <br>";
                //$ret = $rowb["TVALUE"];
                $out['OFI']=$rowb["TVALUE"];
            }
            
        }
    } else {
        $ret = "HWID REPETIDO!!!!";
    }
    return $out;
}



function DevUserForm($ldata) {
    //echo print_r($ldata);
    $forma='<div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-validation">
                                <form class="form-valide" action="#" method="post">
                                    <input type="hidden" id="eldn" value="'.$ldata[0]["duusernname"].'">
                                        <!-- Primer Reglon -->
                                        <div class="form-group row">
                                            <div class="col">
                                            <div class="row"><label class="col-lg-4 col-form-label" for="val-uid">DU Username: </label></div>
                                                <div class="col-lg-6">
                                                    <div class="form-row" id="elinput-'.$cu.'">
                                                        <input type="text" class="form-control" id="val-duusernname" name="val-duusernname" placeholder="Nombre de usuario.." value="'.$ldata[0]["duusernname"][0].'" '.$ldata[0]["duusernname"][0].'>
                                                    </div>'.$gdbinfo.'
                                                </div>
                                            </div>';
                                $cu='dunombre';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">DU Nombre: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                
                                        </div>


//
                            <!-- Segundo Reglon Nombre -->
                            <div class="form-group row">';
                                $cu='dunumeroempleado';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">DU Numero de Empleado: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='oficina';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">DU Oficina: </p></label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                

//                                        


                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
    return $forma;      



}

function UserForm($ldata) {
    $conx=ConectaSQL('ocsweb');
    $connglobal=ConectaSQL('globaldb');
    $mambo=ChkExistMamboUser($ldata[0]["uid"][0],$connglobal);
    if ($mambo == "SI") {
        $gdbinfo = "<div id='NOTAGDB' class='alert alert-primary' role='alert'>Se encontro user en Mambo</div><br><div id='selegdb'></div>";
    } else {
        $gdbinfo = "<div id='NOTAGDB' class='alert alert-danger' role='alert'>No se encontro user en Mambo</div><br><div id='selegdb'></div>";
    }

    error_reporting(1);
    if (isset($ldata[0]["uid"][0])) {
        $mail = $ldata[0]["mail"][0];
        $uid = $ldata[0]["uid"][0];
        if ($ldata[0]['sambapasswordhistory'][0] == "0000000000000000000000000000000000000000000000000000000000000000") {
            $haveSAMBA="SI";
        } else {
            $haveSAMBA="NO";
        }
        $haveINFRAESTRUCTURA="SI";
        //$haveSAMBA=$ldata[0]['sambapasswordhistory'][0];
        /*
        echo "<pre>";
        print_r($ldata);
        echo "</pre>";
        */
        $servs = $ldata[0]["servicios"][0];
        if ($servs == "Drupal") {
            $srvDrypalchk='checked';
        }
        $livemeeting  = $ldata[0]["livemeeting"][0];
        if ($livemeeting  == "OpenVPN") {
            $srvOPENVPNchk='checked';
            $vpnstat='ON';
        } else {
            $vpnstat='OFF';
        }

        $userpassword = $ldata[0]["userpassword"][0];
        $dn=$ldata[0]["dn"];
        $cntaliascuentagoogle=$ldata[0]["aliascuentagoogle"]["count"];
        $aliasesg='';
        $aliasesh='';
        for ($i=0; $i < $cntaliascuentagoogle; $i++) { 
            //echo $ldata[0]["aliascuentagoogle"][$i];
            $aliasesg.='<option value="'.$ldata[0]["aliascuentagoogle"][$i].'">'.$ldata[0]["aliascuentagoogle"][$i].'</option>';

            $aliasesh.='        <tr>
                        <td>'.$ldata[0]["aliascuentagoogle"][$i].'</td>
                        <td>
                            <a class="add" title="Add" data-toggle="tooltip" onclick="AddAlias()"><i class="material-icons">&#xE03B;</i></a>
                            <!--<a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>-->
                            <a class="delete" title="Delete" data-toggle="tooltip" onclick="DelAlias('."'".$ldata[0]["aliascuentagoogle"][$i]."'".')"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>';


        }
        $rouser="readonly";
        $validate='<button type="button" class="btn btn-primary mb-2" onclick="ValidateLDAPass()">Validar</button><input type="hidden" id="passwd" value="'.$userpassword.'">';
        $validate .='<button type="button" class="btn btn-primary mb-2" onclick="ResetLDAPass()">Resetear</button>';

        // RED
        $hwid=GetHardwareIDFromUsername($uid);
        if (preg_match("/^(?:\d+)$/i",$hwid)) {
            $taginfo = "<div id='NOTATAG' class='alert alert-primary' role='alert'>Se encontro TAG hwid => ".$hwid." En OCS</div><br><div id='selevale'></div>";
        } else {
            $taginfo = "<div id='NOTATAG' class='alert alert-danger' role='alert'>No se encontro TAG En OCS</div><br><div id='selevale'></div>";
            $hwid="NO";
        }

        // CHECK AND VALIDATE MAC
        //$selectmacs=GetAvailHardware('lanmac',$uid);
        if (! isset($ldata[0]["lanmac"][0])) {
            $ldata[0]["lanmac"][0] = "NO EXISTE MAC LAN EN LDAP";
            $alertlan="YES";
            if ($hwid != "NO") {
                //$ocsmacs=GetAvailHardware
                $selectmacs=GetAvailHardware('lanmac',$uid);
            }
            $validmac="NO";
        } else {
            if (filter_var($ldata[0]["lanmac"][0], FILTER_VALIDATE_MAC)) {
                $validmac="YES";
            } else {
                $validmac="NO";
            }
        }

        // CHECK AND VALIDATE MAC
        //$selectwmacs=GetAvailHardware('wifimac',$uid);
        if (! isset($ldata[0]["wifimac"][0])) {
            $ldata[0]["wifimac"][0] = "NO EXISTE MAC WIFI LAN EN LDAP";
            $alertwifi="YES";
            if ($hwid != "NO") {
                //$ocsmacs=GetAvailHardware
                $selectwmacs=GetAvailHardware('wifimac',$uid);
            }
            $validwmac="NO";
        } else {
            if (filter_var($ldata[0]["wifimac"][0], FILTER_VALIDATE_MAC)) {
                $validwmac="YES";
            } else {
                $validwmac="NO";
            }
        }        

        // LapizLanIP
        $lapizlanip='<a href="#" onclick="UVal('."'$dn'".','."'lanip'".')"><span class="fa fa-pencil"></span>';
        if (! isset($ldata[0]["lanip"][0])) {
            if ($validmac == "NO") {
                $lapizlanip='<span class="fa fa-exclamation-triangle"><span class="badge badge-pill badge-danger">DECLARE MAC</span>';
            }
            $ldata[0]["lanip"][0] = "NO EXISTE IP LAN EN LDAP";
        }


        GetAvailableIP($ldata[0]['oficina'][0],"lanip");
        //return false;
        //print_r(array_count_values($ldata));
        //print_r($ldata);

        if (filter_var($ldata[0]["lanip"][0], FILTER_VALIDATE_IP)) {
           $ipin='<INPUT type="hidden" id="validlanip" value="YES"><INPUT type="hidden" id="lanipval" value="'.$ldata[0]["lanip"][0].'">'; 
        } else {
            $ipin='<INPUT type="hidden" id="validlanip" value="NO"><INPUT type="hidden" id="lanipval" value="NO">'; 
        }
    }


    $forma='<div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="#" method="post">
                        <input type="hidden" id="eldn" value="'.$ldata[0]["dn"].'">
                            <!-- Primer Reglon -->
                            <div class="form-group row">
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-uid">Username: </label></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-uid" name="val-uid" placeholder="Nombre de usuario.." value="'.$uid.'" '.$rouser.'>
                                        </div>'.$gdbinfo.'
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-userpassword"></small>Validar Password: (Si hace cambios necesita recargar para validar</small>)</label></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="password" class="form-control" id="val-userpassword" name="val-userpassword" placeholder="Teclear Password.." >'.$validate.'
                                        </div>
                                    </div>

                                </div>
                            </div>    
                            <!-- Segundo Reglon Nombre -->
                            <div class="form-group row">';
                                $cu='givenname';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Nombre: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='sn';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Apellido: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Tercer Reglon Oficina y No Empleado -->
                            <div class="form-group row">';
                                $cu='noempleado';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Numero de Empleado: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='oficina';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">Oficina: </p></label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Cuarto Reglon Travelling y Accesos de Red -->
                            <div class="form-group row">';
                                $cu='travelling';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Travelling: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='accesosdered';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">Accesos de red: </p></label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Quinto Reglon Scrup level y Anfitriron -->
                            <div class="form-group row">';
                                $cu='nivelscup';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Nivel SCRUP: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='anfitrion';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Anfitrion: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Sexto Reglon Licencia Google y Mail -->
                            <div class="form-group row">';
                                $cu='licenciagoogle';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Licencia Google: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='mail';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Mail: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Septimo Reglon Extension y tag OCS -->
                            <div class="form-group row">';
                                $cu='extension';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Extension asignada: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='aliasgoogle';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Alias Google: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Octavo Reglon LAN -->
                            <div class="form-group row">';
                                $cu='lanip';
                                $forma .='
                                <div class="col">'.$ipin.'
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">IP LAN (Cable): </label><div id="edit-'.$cu.'">'.$lapizlanip.'</a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'><br>'.$taginfo.'
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='lanmac';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">MAC Address LAN: </p></label><div id="edit-'.$cu.'"><a href="#/"" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'><br><br>'.$selectmacs.'
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Noveno Reglon WIFI -->
                            <div class="form-group row">';
                                $cu='wifiip';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Wifi IP: <small>(Calculada, No LDAP)</small> </label><div id="edit-'.$cu.'"><!--<a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a>--></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='wifimac';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">Wifi MAC: </p></label><div id="edit-'.$cu.'"><a href="#/"" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'><br><br>'.$selectwmacs.'
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Decimo Reglon WIFI -->
                            <div class="form-group row">';
                                $cu='puesto';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Puesto: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='SAMBA';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Acceso Samba: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$haveSAMBA.'" value="'.$haveSAMBA.'" '.$haveSAMBA.' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                

                            <!-- Onceavo Reglon Servicios (Drupal) -->
                            <div class="form-group row">';
                            	if ($haveINFRAESTRUCTURA == "SI") {
                            		$ifchk="checked";
                            	}
                                $cu='Drupal';
                                $cus='Samba';
                                $cui='INFRAESTRUCTURA';
                                $vpn='OpenVPN';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Servicios: </label></div>
                                    <div class="col-lg-6">
                                        <div class="form-row form-check-label" id="elservicio-'.$cu.'">
                                            <input type="checkbox" class="form-check-input" id="val-'.$cu.'" name="val-'.$cu.'" onclick="EnableService('."'$dn'".','."'$cu'".','."'$servs'".')"  '.$srvDrypalchk.' > . .  Drupal 
                                        </div>
                                        <div class="form-row form-check-label" id="elservicio-INFRAESTRUCTURA">
                                            <input type="checkbox" class="form-check-input" id="val-INFRAESTRUCTURA" '.$ifchk.' name="val-INFRAESTRUCTURA" onclick="EnableService('."'$dn'".','."'$cui'".','."'$servs'".')"  '.$srvDrypalchk.' > . .  INFRAESTRUCTURA 
                                        </div>
                                        <div class="form-row form-check-label" id="elservicio-OPENVPN">
                                            <input type="checkbox" class="form-check-input" id="val-OPENVPN" '.$srvOPENVPNchk.' name="val-OPENVPN" onclick="EnableService('."'$dn'".','."'$vpn'".','."'$vpnstat'".')"  '.$srvOPENVPNchk.' > . .  OPENVPN 
                                        </div>

                                        <!--
                                        <div class="form-row form-check-label" id="elservicio-'.$cus.'">
                                            <input type="checkbox" class="form-check-input" id="val-'.$cus.'" name="val-'.$cus.'" onclick="EnableService('."'$dn'".','."'$cus'".','."'$servs'".')"  '.$srvDrypalchk.' > . .  Drupal 
                                        </div>
                                        -->
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-row form-check-label" id="nuke-'.$cu.'">
                                             <button type="button" class="btn btn-danger" onclick="DeleteUser('."'$dn'".')">Borrar</button>
                                        </div>
                                    </div>

                                </div>
                                ';
                                $cu='noone';
                                $forma .='
                                <div class="col">
                                    <!--<div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">XXXX: </label><div id="edit-'.$cu.'"><a href="#" onclick="UVal('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>-->
                                </div>
                            </div>                                




<!--

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="val-aliascuentagoogle">Alias Google </label>
                                <div class="col-lg-6">
                                    <select class="form-control" id="aliascuentagoogle" name="val-aliascuentagoogle" name="aliascuentagoogle" multiple>'.$aliasesg.'</select> 
                                </div>
                            </div>
-->



    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Aliases de Google</h2></div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Agregar</button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <!--<th>Department</th>
                        <th>Phone</th>-->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>'.$aliasesh.'</tbody>
            </table>
        </div>
    </div>     



















                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

$aforma = array($forma, $alertlan, $alertwifi);
return $aforma;

}

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

function CheckSMBServiceForUser($dn) {
    $ldapBind=ConectaLDAP();
    if (preg_match('/uid=(\w+),ou=People/', $dn, $match) == 1) {
        $uid = $match[1];
    }    
    $srchSamba=ldap_search($ldapBind, "dc=transportespitic,dc=com","(&(objectClass=sambaSamAccount)(uid=".$uid."))");
    $tieneSamba = ldap_count_entries($ldapBind, $srchSamba);
    if($tieneSamba == 0){
        $RES="NO";
    } else {
        $RES="SI";
    }
    return $RES;
}


function UpdateLDAPVAl($dn,$value,$vname) {
    $ldapBind=ConectaLDAP();
    $values[$vname][0] = array();
    $values[$vname][0]=$value;
    //ldap_modify($ldapBind, $dn, $values);
    if (@ldap_modify($ldapBind, $dn, $values)) {
        $RES="YES";
    } else {
         $RES=ldap_error($ldapBind);
    }
    return $RES;
}

function AppendLDAPVAl($dn,$value,$vname) {
    $ldapBind=ConectaLDAP();
    $values[$vname][0] = array();
    $values[$vname][0]=$value;
    //print_r($values);
    //ldap_modify($ldapBind, $dn, $values);
    if (@ldap_mod_add($ldapBind, $dn, $values)) {
        $RES="YES";
    } else {
         $RES=ldap_error($ldapBind);
    }
    return $RES;
}

function DeleteLDAPVAl($dn,$value,$vname) {
    $ldapBind=ConectaLDAP();
    $values[$vname][0] = array();
    $values[$vname][0]=$value;
    //print_r($values);
    //echo $value."xxx";
    //echo $dn;
    //ldap_modify($ldapBind, $dn, $values);
    if (@ldap_mod_del($ldapBind, $dn, $values)) {
        $RES="YES";
    } else {
         $RES=ldap_error($ldapBind);
    }
    return $RES;
}

function DeleteLDAPUser($dn) {
    $ldapBind=ConectaLDAP();
    ldap_error($ldapBind);
    if (@ldap_delete($ldapBind, $dn)) {
        $RES="YES";
    } else {
         $RES=ldap_error($ldapBind).$dn;
    }
    return $RES;
}

function GetLastLDAPUid() {
    $ldapBindx=ConectaLDAP();
    $sr=ldap_search($ldapBindx, "ou=People,dc=transportespitic,dc=com", "uid=*");
    $numero=ldap_count_entries($ldapBindx, $sr);
    $info = ldap_get_entries($ldapBindx, $sr);
    $stack=Array();
    for ($i=0; $i<$info["count"]; $i++) {
        array_push($stack,$info[$i]['uidnumber'][0]);
    }
    $max=max($stack);
    return  $max;
}



function UpdateDelFeria($dn,$value,$vname) {
    $ldapBind=ConectaLDAP();
    $removal = array(
        //"lanip"=>"192.168.140.169",
        "lanmac"=>"00:11:22:33:44:58"
    );
    $groupDn='uid=jferia,ou=People,dc=transportespitic,dc=com';
    $ldapBind=ConectaLDAP();
    //$values['iplan'][0] = array();
    $values['lanmac'][0] = array();
    ldap_mod_del($ldapBind, $groupDn, $removal);
}

function GetAvailableIP($ofi,$tipo) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    set_time_limit(30);
    $arrayip= array();
    $result = ldap_search($ldapconn,$LDAPUserBase, "(oficina=$ofi)");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    for ($i=0; $i<$ldata["count"]; $i++) {
        if (isset($ldata[$i][$tipo][0])) {
            array_push($arrayip,$ldata[$i][$tipo][0]);
            //echo $ldata[$i][$tipo][0];

        }
    }
    sort($arrayip);
    //print_r($arrayip);
    return false;    
    //echo $out;
    //return $out;
}


function GetAvailableIPFromSegment($segment,$tipo) {
    include 'configuraciones.class.php';
    $err='';
    $data='';
    $out='';
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    set_time_limit(30);
    $arrayip= array();
    //$result = ldap_search($ldapconn,$LDAPUserBase, "($tipo=192.168.$segment.*)");
    $result = ldap_search($ldapconn,$LDAPUserBase, "$tipo=*");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    // Generamos array con IPs Registradas en el segmento
    for ($i=0; $i<$ldata["count"]; $i++) {
        if (isset($ldata[$i][$tipo][0])) {
            if(preg_match("/192\.168\.$segment\./",$ldata[$i][$tipo][0])) {
                array_push($arrayip,$ldata[$i][$tipo][0]);
            }                
        }
    }
    sort($arrayip);
    $fourth="";
    for ($x = 100; $x <= 199; $x++) {
        //echo "testing $x <br>\n";
        if (in_array("192.168.$segment.$x", $arrayip)) {
            //echo "Existe $x <br>\n";
        } else {
            // Bingo!
            //echo "NO Existe $x <br>\n";
            $fourth="192.168.$segment.$x";
            break;
        }
    }
    //print_r($arrayip);
    //return false;    
    //echo $out;
    return $fourth;
}

function GetOfficeAbrevs($db) {
    $sa = array();
    $conn=ConectaSQL('firewall');
    $sql = "select abrev from oficinas";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($sa, $row["abrev"]);
        }
    }
    array_push($sa, "SIS");
    array_push($sa, "DFA");
    array_push($sa, "DAF");
    return $sa;

}


function plainPassword(): string
{
    $numbers = array_rand(range(0, 9), rand(3, 5));
    $uppercase = array_rand(array_flip(range('A', 'Z')), rand(2, 4));
    $lowercase = array_rand(array_flip(range('a', 'z')), rand(3, 4));
    $special = array_rand(array_flip(['@', '#', '$', '!', '%', '*', '?', '&']), rand(3, 2));

    $password = array_merge(
        $numbers,
        $uppercase,
        $lowercase,
        $special
    );

    shuffle($password);

    return implode($password);
}


function NewDevUserForm() {
    $rouser="";
    $cu="NU";
    $CmbOfi="";
    $abs=GetOfficeAbrevs('x');
    foreach ($abs as $value) {
        $CmbOfi .= '<OPTION VALUE="'.$value.'">'.$value.'</OPTION>';
    }
    $forma='<div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" name="newdevuser" id="newdevuser" action="#" method="post">
                            <!-- CARD -->
                            <div class="card"><div class="card-body"><h4 class="card-title">Informacion del Usuario</h4>
                            <!-- Primer Reglon -->
                            <div class="form-group row">';
                            $cu='duoficina';
                            $forma .='
                            <div class="col">
                                <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">Oficina: </p></label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                <div class="col-lg-6">
                                    <div class="form-row" id="elinput-'.$cu.'">
                                        


                                        <select style="width:5" class="form-control" name="val-'.$cu.'" id="val-'.$cu.'" >
                                            <option value="SELECCIONE">SELECCIONE</option>'.$CmbOfi.'

                                        </select>




                                    </div>
                                </div>
                            </div>
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-dunombre">Nombre Completo:</label></div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-dunombre" name="val-dunombre" placeholder="Autogenerado" value="'.$dunombre.'" readonly>
                                    </div>
                                </div>
                            </div>


                            <!-- Segundo Reglon Oficina y No Empleado -->
                            <div class="form-group row">';
                                $cu='dunumeroempleado';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Numero de Empleado: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValnn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'"  '.$ldata[0][$cu][0].' onchange="validarinput('."'numero','$cu'".','."'SI'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='duusernname';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Username: </label></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="Nombre de usuario" onchange="validarinput('."'palabra','$cu'".','."'SI'".')" '.$rouser.' readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            </form>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Acciones</h4>
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <div class="form-check mb-3 ">
                                                    <label class="form-check-label">
                                                        <button type="button" id="BtnSaveNewDevUser" class="btn btn-primary mb-2" onclick="SaveNewDevUser()">Agregar</button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    ';     
    //echo $forma;                       
    return $forma;                            

}


//

function NewCellForm() {

    //2-Crear telefono en ldap con la ofi del usuario y el consecutivoque le toca el valor de imei sera PORASIGNAR, si existe en api ponerle valor de imei, serie, y el usuario, si no existe el usuario validar que existe en deviceusers, tambien verificar que no tenga otro celular asignado, capturar el numero de telefono, y el device dept

    $rocell="";
    $cu="NU";
    $cn="NU";
    $CmbOfi="";
    $abs=GetOfficeAbrevs('x');
    foreach ($abs as $value) {
        $CmbOfi .= '<OPTION VALUE="'.$value.'">'.$value.'</OPTION>';
    }
    $forma='<div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" name="newcell" id="newcell" action="#" method="post">
                            <!-- CARD -->
                            <div class="card"><div class="card-body"><h4 class="card-title">Informacion del Dispositivo</h4>
                            <!-- Primer Reglon -->
                            <div class="form-group row">';
                                $cu='newtag';
                                $rouser="";
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Tag: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" onchange="validarinput('."'palabrasp','$cu'".','."'NO'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $rouser="";
                                $cu='deviceassignedto';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Device User: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" onchange="validarinput('."'validadeviceuser','$cu'".','."'NO'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Segundo Reglon  -->
                            <div class="form-group row">';
                                $cu='devicenumber';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Numero de telefono: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'"  '.$ldata[0][$cu][0].' onchange="validarinput('."'numero','$cu'".','."'SI'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='oficina';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">Oficina: </p></label><div id="edit-'.$cu.'"><a href="#" onclick="SelCelOfi('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            


                                            <select style="width:5" class="form-control" name="val-'.$cu.'" id="val-'.$cu.'" onchange="SelCelOfi('."'$dn'".','."'$cu'".')">
                                                <option value="SELECCIONE">SELECCIONE</option>'.$CmbOfi.'

                                            </select>




                                        </div>
                                    </div>
                                </div>
                            </div>                                

                            <!-- Tercer Reglon  -->
                            <div class="form-group row">';
                                $cu='devicedept';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Departamento: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'"  '.$ldata[0][$cu][0].' onchange="validarinput('."'palabrasp','$cu'".','."'SI'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $rouser="";  //
                                $cu='devicimei';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Device IMEI: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="PORASIGNAR" onchange="validarinput('."'validadeviceuser','$cu'".','."'NO'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                        </form>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Acciones</h4>
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <div class="form-check mb-3 ">
                                                    <label class="form-check-label">
                                                        <button type="button" id="BtnSaveNewCell" class="btn btn-primary mb-2" onclick="SaveNewCell()">Agregar</button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>';
    return $forma;

}
//



function NewUserForm() {
    $rouser="";
    $cu="NU";
    $CmbOfi="";
    $abs=GetOfficeAbrevs('x');
    $pass=plainPassword();
    $newpass=randomPassword();
    foreach ($abs as $value) {
        $CmbOfi .= '<OPTION VALUE="'.$value.'">'.$value.'</OPTION>';
    }
    $forma='<div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" name="newuser" id="newuser" action="#" method="post">
                            <!-- CARD -->
                            <div class="card"><div class="card-body"><h4 class="card-title">Informacion del Usuario</h4>
                            <!-- Primer Reglon -->
                            <div class="form-group row">';
                                $cu='uid';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Username: </label></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-uid" name="val-'.$cu.'" placeholder="Nombre de usuario" onchange="validarinput('."'palabra','$cu'".','."'SI'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-userpassword">Password:</label></div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-userpassword" name="val-userpassword" placeholder="Autogenerado" value="'.$newpass.'">
                                    </div>
                                </div>
                            </div>

                            <!-- Segundo Reglon Nombre -->
                            <div class="form-group row">';
                                $cu='givenname';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Nombre: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" onchange="validarinput('."'palabrasp','$cu'".','."'NO'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='sn';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Apellidos: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" onchange="validarinput('."'palabraspforce','$cu'".','."'NO'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tercer Reglon Oficina y No Empleado -->
                            <div class="form-group row">';
                                $cu='noempleado';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Numero de Empleado: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'"  '.$ldata[0][$cu][0].' onchange="validarinput('."'numero','$cu'".','."'SI'".')" '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='oficina';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">Oficina: </p></label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            


                                            <select style="width:5" class="form-control" name="val-'.$cu.'" id="val-'.$cu.'">
                                                <option value="SELECCIONE">SELECCIONE</option>'.$CmbOfi.'

                                            </select>




                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Cuarto Reglon Travelling y Accesos de Red -->
                            <div class="form-group row">';
                                $cu='travelling';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Travelling: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">


                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="NO">NO</option>
                                                <option value="SI">SI</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='accesosdered';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">Accesos de red: </p></label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">

                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="1">WEB LIBRE</option>
                                                <option value="2">TOTAL</option>
                                                <option value="3" SELECTED >PAGINAS</option>
                                                <option value="5">VOLVO TDI</option>
                                                <option value="8">ADMIN</option>
                                                <option value="9">SIN ACCESO</option>
                                            </select>


                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Quinto Reglon Scrup level y Anfitriron -->
                            <div class="form-group row">';
                                $cu='nivelscup';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Nivel SCRUP: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='samba';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Acceso a Samba: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <!--<input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>-->
                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="SELECCIONE">SELECCIONE</option>
                                                <option value="0">NO</option>
                                                <option value="1">SI</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Sexto Reglon Licencia Google y Mail -->
                            <div class="form-group row">';
                                $cu='licenciagoogle';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Licencia Google: </label>
                                    <!--<div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div>--></div>
                                    <div class="col-lg-6">
                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="SELECCIONE">SELECCIONE</option>
                                                <option value="NINGUNA">NINGUNA</option>
                                                <option value="BASIC">BASIC</option>
                                                <option value="LITE">LITE</option>
                                                <option value="BUSSINESS">BUSSINESS</option>
                                            </select>
                                    </div>
                                    <!--
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                    -->                                    
                                </div>
                                ';
                                $cu='mail';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Mail: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Septimo Reglon Extension y tag OCS -->
                            <div class="form-group row">';
                                $cu='extension';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Extension asignada: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='aliasgoogle';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Alias Google: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="user1" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Octavo Reglon LAN -->
                            <div class="form-group row">';
                                $cu='lanip';
                                $forma .='
                                <div class="col">'.$ipin.'
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">IP LAN (Cable): </label><div id="edit-'.$cu.'">'.$lapizlanip.'</a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'><br>'.$taginfo.'
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='lanmac';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">MAC Address LAN: </p></label><div id="edit-'.$cu.'"><a href="#/"" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <!--<input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" onchange="validarinput('."'mac','$cu'".','."'SI'".')" '.$rouser.'><br>--><input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'"  onchange="validarinput('."'mac','$cu'".','."'SI'".')" readonly><div id="lanmacsel">Waiting for info</div><div id="selnetdiv"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Noveno Reglon WIFI -->
                            <div class="form-group row">';
                                $cu='wifiip';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Wifi IP: <small>(Calculada, No LDAP)</small> </label><div id="edit-'.$cu.'"><!--<a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a>--></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='wifimac';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'"><p class="text-danger">MAC Address Wifi: </p></label><div id="edit-'.$cu.'"><a href="#/"" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <!--<input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" onchange="validarinput('."'mac','$cu'".','."'SI'".')" '.$rouser.'>--><input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'"  onchange="validarinput('."'mac','$cu'".','."'SI'".')" readonly><div id="wifimacsel">Waiting for info</div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                            <!-- Decimo Reglon  -->
                            <div class="form-group row">';
                                $cu='puesto';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Puesto: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                                ';
                                $cu='sambaacctflags';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">sambaacctflags: </label><div id="edit-'.$cu.'"><a href="#" onclick="UValn('."'$dn'".','."'$cu'".')"><span class="fa fa-pencil"></span></a></div></div>
                                    <div class="col-lg-6">
                                        <div class="form-row" id="elinput-'.$cu.'">
                                            <input type="text" class="form-control" id="val-'.$cu.'" name="val-'.$cu.'" placeholder="'.$cu.'" value="'.$ldata[0][$cu][0].'" '.$ldata[0][$cu][0].' '.$rouser.'>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Onceavo Reglon SERVICIOS -->
                            <div class="form-group row">';
                                $cu='Drupal';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Drupal: </label>
                                    </div>
                                    <div class="col-lg-6">
                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="SELECCIONE">SELECCIONE</option>
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                    </div>
                                </div>
                                ';
                                $cu='EmailService';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Email Corp: </label>
                                    </div>
                                    <div class="col-lg-6">
                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="SELECCIONE">SELECCIONE</option>
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                    </div>
                                </div>
                            </div>                                

                            <!-- Doceavo Reglon -->
                            <div class="form-group row">';
                                $cu='PerfilDrupal';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Perfil Drupal Primario: </label>
                                    </div>
                                    <div class="col-lg-6">
                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="SELECCIONE">SELECCIONE</option>
                                                <option value="NINGUNO">NINGUNO</option>
                                                <option value="GerenteCyC">Gerente CyC</option>
                                                <option value="AuxiliarCyC">Auxiliar CyC</option>
                                                <option value="JefeCyC">Jefe CyC</option>
                                                <option value="Gerente">Gerente</option>
                                                <option value="GerenteRegional">Gerente Regional</option>
                                                <option value="AuxiliarGerenteCyC">Auxiliar Gerente CyC</option>
                                                <option value="DirectorDAF">Director DAF</option>
                                            </select>
                                    </div>
                                </div>
                                ';
                                $cu='Empresa';
                                $forma .='
                                <div class="col">
                                    <div class="row"><label class="col-lg-4 col-form-label" for="val-'.$cu.'">Empresa: </label>
                                    </div>
                                    <div class="col-lg-6">
                                            <select style="width:5" class="form-control" name="'.$cu.'" id="'.$cu.'">
                                                <option value="tpitic">Transportes Pitic</option>
                                                <option value="tractoremolques">Tractoremolques Del Noroeste</option>
                                            </select>
                                    </div>
                                </div>
                            </div>                                

</form>



                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Acciones</h4>
                                        <div class="basic-form">
                                            <div class="form-group">
                                                <div class="form-check mb-3 ">
                                                    <label class="form-check-label">
                                                        <button type="button" id="BtnSaveNewUser" class="btn btn-primary mb-2" onclick="SaveNewUser()">Agregar</button>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

    ';     
    //echo $forma;                       
    return $forma;
}


function CheckExistentValueLDAP($base,$what,$val) {
    include 'configuraciones.class.php';
    if ($what == "dunumeroempleado") {
        // Si se esta registrando un device user validar numero de enpleado en ldap de usuarios
        $what="noempleado";
    }
    if ($what == "dunumeroempleadoOriginal") {
        $what="dunumeroempleado";
        $LDAPUserBase="ou=DeviceUsers,dc=transportespitic,dc=com";
    }
    if ($what == "duusernname") {
        $LDAPUserBase="ou=DeviceUsers,dc=transportespitic,dc=com";
    }
    if ($what == "deviceassignedto") {
        $LDAPUserBase="ou=Celulares,ou=Devices,dc=transportespitic,dc=com";
    }



    $ldapconn=ConectaLDAP();
    ini_set('display_errors', 'On');
    set_time_limit(30);
    $result = ldap_search($ldapconn,$LDAPUserBase, "$what=$val");
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    //echo "$what=$val  > ".$ldata['count'];
    //print_r($ldata);
    if ($ldata['count'] > 0) {
        return "NO";
    } else {
        return "YES";
    }
}

function GetThirdOctetForOficina($ofi,$db) {
    global $conn;
    $sql = "select idoficina,lan from oficinas where abrev = '$ofi' ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo $row["idoficina"];
            $oc = explode('.', $row["lan"]);
            //echo "xxxx".$oc[2];
            $to=$oc[2];
            if (strlen($oc[2]) < 1) {
                $to='NO'; 
            }
            return $to;
        }
    }
 }


function GetAvailHardware($name,$tag) {
    $conn=ConectaSQL('ocsweb');
    $ahw=array();
    $cnt=0;
    $sq="";
    $out="<SELECT ID='$name-sel' NAME='$name-sel' style='width:5' class='form-control' onchange=".'"'."ValidateMacSet('$name');".'"'."><OPTION NAME='SELECCIONE' value='SELECCIONE'>SELECCIONE</OPTION><OPTION NAME='NO' value='NO'>NO APLICA</OPTION><OPTION NAME='MANUAL' value='MANUAL'>CAPTURA MANUAL</OPTION>";
    //$sql = "select HARDWARE_ID from accountinfo where tag='acota' or tag='jfavila'";
    $sql = "select HARDWARE_ID from accountinfo where tag='$tag'";
    $result = $conn->query($sql);
    //echo $result->num_rows;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ret = $row["HARDWARE_ID"];
            if ($cnt == 0) {
                $sq .= "HARDWARE_ID='".$row["HARDWARE_ID"]."' ";
            } else {
                $sq .= "or HARDWARE_ID='".$row["HARDWARE_ID"]."' ";
            }
            array_push($ahw, $row["HARDWARE_ID"]);
            $cnt++;
        }
        //print_r($ahw);
        $q="select HARDWARE_ID,ID,DESCRIPTION,MACADDR from networks where ( ".$sq.") and DESCRIPTION NOT LIKE '%TAP-WIN%' and DESCRIPTION NOT LIKE '%Bluetooth%';";
        $resultb = $conn->query($q);
        //echo $resultb->num_rows;
        if ($resultb->num_rows > 0) {
            while($rowb = $resultb->fetch_assoc()) {
                //print_r($rowb);
                $out.="<OPTION NAME='MAC' VALUE='".$rowb["MACADDR"]."'>'".$rowb["HARDWARE_ID"]."' '".$rowb["MACADDR"]."' '".$rowb["DESCRIPTION"]."'</OPTION>";
            }
        }

        //echo $q;
    } else {
        $ret="NO";
    }
    $out.="</SELECT><br><div ID='$name-netscombo' NAME='$name-netscombo'></div><button id='".$name."-BtnMacChn' type='button' class='btn mb-1 btn-primary btn-xs' onclick=".'"'."SaveMacChange('$name');".'"'.">Guarda</button>";
    //return $pila;
    //return $ret;
    //echo "12345".$conn;
    //echo $out;
    return $out;
}

function GetOCSTAG($serial,$conn) {
    $fnt1="<font face='Trebuchet MS, Arial, Helvetica' size='1'>";
    $sql = "select HARDWARE_ID from bios where SSN='$serial'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $sqlb = "select TAG from accountinfo where HARDWARE_ID='".$row["HARDWARE_ID"]."'";
            $resultb = $conn->query($sqlb);
            if ($resultb->num_rows > 0) {
                while($rowb = $resultb->fetch_assoc()) {
                    //return "<td> $fnt1 ***".$rowb["TAG"]."</td><td> $fnt1 ***".$row["HARDWARE_ID"]."</td>";
                    return $rowb["TAG"];
                }
            } else {
                return "NO TAG";
            }
        }
    } else {
        return "NOTFOUND";
    }
}

function GetOCSTAGFromHwId($hwid,$conn) {
    $sql = "select TAG from accountinfo where HARDWARE_ID='$hwid'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row["TAG"];
        }
    }
}


function GetOCSHwIDFromIMEI($imei,$conn) {
    $fnt1="<font face='Trebuchet MS, Arial, Helvetica' size='1'>";
    $sql = "select HARDWARE_ID from sim where DEVICEID='$imei'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row["HARDWARE_ID"];
        }
    } else {
        return "NOTFOUND_or_DUP";
    }
}

function GetOCSImeiFromTag($tag,$conn) {
    $sql = "select DEVICEID from sim where HARDWARE_ID='$tag'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row["DEVICEID"];
        }
    } else {
        return "NOTFOUND_or_DUP";
    }
}


function CorrectOCSTAG($goodtag,$wrongtag,$conn) {
    echo $sql = "UPDATE accountinfo set TAG='".$goodtag."' where TAG='".$wrongtag."'";
    $result = $conn->query($sql);
}




function GetOCSInfoFromTAG($tag,$what,$conn) {
    $fnt1="<font face='Trebuchet MS, Arial, Helvetica' size='1'>";
    $sql = "select $what from accountinfo where TAG='$tag'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row[$what];
        #    $sqlb = "select TAG from accountinfo where HARDWARE_ID='".$row["HARDWARE_ID"]."'";
        #    $resultb = $conn->query($sqlb);
        #    if ($resultb->num_rows > 0) {
        #        while($rowb = $resultb->fetch_assoc()) {
        #            return "<td> $fnt1 ***".$rowb["TAG"]."</td><td> $fnt1 ***".$row["HARDWARE_ID"]."</td>";
        #        }
        #    } else {
        #        return "NO TAG";
        #    }
        } 
    } else {
        return "NO TAG ON OCS";
    }
}


function GetUserFromDN($dn) {
    if (preg_match("/uid=(.+)\,ou=People,/i",$dn,$mat)) {
        $val=$mat['1'];
    } else {
        $val="NO";
    }
    return $val;
}

function ChkExistMamboUser($user,$conn) {
    $sql="SELECT * FROM only_users WHERE username = '".$user."'";
    $result = $conn->query($sql);
    //echo $result->num_rows;
    if ($result->num_rows > 0) {
        return "SI";
    } else {
        return "NO";
    }        
}

function DeleteMamboUser($user,$conn) {
    $sql="DELETE FROM only_users WHERE username = '".$user."'";
    $result = $conn->query($sql);
    if ($result) {
        return "SI";
    } else {
        return "NO";
    }        
}


function ChgPasswdMamboUser($user,$pass) {
    $conn=ConectaSQL('globaldb');
    $sql="UPDATE only_users set password='$pass' WHERE username = '".$user."'";
    $result = $conn->query($sql);
    if ($result) {
        return "SI";
    } else {
        //return "NO";
         return$conn -> error;
    }        
}


function hash_password($password) {
    $salt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',4)),0,4);
    return '{SSHA}' . base64_encode(sha1( $password.$salt, TRUE ). $salt);
}


function randomPassword() {
    $alphabet = 'a#bcdefghijklmnopqrstuvwx#yzABCDEFGHIJKLMNOPQRSTUVWX#YZ1234567890#';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function ResetPasswordLDAP($user,$pass) {
    $ldapconn=ConectaLDAP();
    $shapass = "{SHA}".base64_encode(sha1($pass, TRUE));
    $sambaNTPassword = strtoupper(hash('md4', iconv('UTF-8','UTF-16LE',$pass)));
    $update['userPassword']=$shapass;
    $update['sambaNTPassword']=strtoupper(hash('md4', iconv('UTF-8','UTF-16LE',$pass)));
    $userldap = "uid=".$user.",ou=People,dc=transportespitic,dc=com";
    $mod=ldap_modify($ldapconn,$userldap, $update);
    return $mod;
}


//function GetCellsFromLDAP($base,$how) {
function GetCellsFromLDAP($como) {
    include 'configuraciones.class.php';
    $how="array";
    $err='';
    $data='';
    $out='';
    if ($how == "array") {
        $out=array();
        $outb=array();
    }
    $ldapconn=ConectaLDAP();
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    set_time_limit(30);
    $array1= array();
    //echo $how;
    if ($como == "active" ) {
        $result = ldap_search($ldapconn,"ou=Celulares,ou=Devices,dc=transportespitic,dc=com", "(!(deviceoffice=BAJA_*))") or die ("Error in search query: ".ldap_error($ldapconn));
    } else {
        $result = ldap_search($ldapconn,"ou=Celulares,ou=Devices,dc=transportespitic,dc=com","(DeviceTAG=*)");
    }
    $err=ldap_error($ldapconn);
    $ldata = ldap_get_entries($ldapconn, $result);
    $ldata["count"]."<br>";
    //echo "<pre>";
    //print_r($ldata);
    //echo "</pre>";

    for ($i=0; $i<$ldata["count"]; $i++) {
        //array_push($array1,$ldata[$i][$what][0]);
        //echo "<pre>";
        //print_r($ldata);
        //echo "</pre>";        
        if ($how == "htmltable") {
            $out .= "<tr><td>".$ldata[$i]['uid'][0]."</td></tr>";    
        }
        if ($how == "array") {
            array_push($out,$ldata[$i]['devicelastseen'][0]);
            $serie=$ldata[$i]['deviceserial'][0];
            $imei=$ldata[$i]['deviceimei'][0];
            //$tag=$ldata[$i]['devicetag'][0];
            //echo "perroi ".$ldata[$i]['devicetag'][0]."--".$imei."--"."<br>";
            $outb[$imei]['devicelastseen']=$ldata[$i]['devicelastseen'][0];
            if (isset($ldata[$i]['deviceserial'][0])) {
                $outb[$imei]['deviceserial']=$ldata[$i]['deviceserial'][0];
            } else {
                $outb[$imei]['deviceserial'] = "PORASIGNAR";
            }
            if (isset($ldata[$i]['deviceimei'][0])) {
                $outb[$imei]['deviceimei']=$ldata[$i]['deviceimei'][0];
            } else {
                $outb[$imei]['deviceimei'] = "PORASIGNAR";
            }
            $outb[$imei]['devicetag']=$ldata[$i]['devicetag'][0];
            $outb[$imei]['deviceimei']=$ldata[$i]['deviceimei'][0];
            $outb[$imei]['deviceassignedto']=$ldata[$i]['deviceassignedto'][0];
            $outb[$imei]['deviceoffice']=$ldata[$i]['deviceoffice'][0];
        }
        //return false;
    }
    //echo $out;
    return $outb;
}



/*
Array
(
    [count] => 1
    [0] => Array
        (
            [employeetype] => Array
                (
                    [count] => 1
                    [0] => user
                )

            [0] => employeetype
            [1] => objectclass
            [gidnumber] => Array
                (
                    [count] => 1
                    [0] => 100
                )

            [2] => gidnumber
            [uidnumber] => Array
                (
                    [count] => 1
                    [0] => 106655
                )

            [3] => uidnumber
            [respuestasec] => Array
                (
                    [count] => 2
                    [0] => cashito
                    [1] => cashito@tpitic.com.mx
                )

            [4] => respuestasec
            [maxmsgsize] => Array
                (
                    [count] => 1
                    [0] => 3.5
                )

            [5] => maxmsgsize
            [roomnumber] => Array
                (
                    [count] => 1
                    [0] => SIS
                )

            [6] => roomnumber
            [nivelhd] => Array
                (
                    [count] => 1
                    [0] => 1
                )

            [7] => nivelhd
            [accesovh] => Array
                (
                    [count] => 1
                    [0] => asd
                )

            [8] => accesovh
            [mobiledev] => Array
                (
                    [count] => 3
                    [0] => 00:1C:BF:05:58:FA
                    [1] => F8:F1:B6:BB:DA:C5
                    [2] => 54:E6:FC:C6:BE:B1
                )

            [9] => mobiledev
            [sambasid] => Array
                (
                    [count] => 1
                    [0] => S-1-5-21-2286529612-1239631486-3098793819-1002
                )

            [10] => sambasid
            [sambapasswordhistory] => Array
                (
                    [count] => 1
                    [0] => 0000000000000000000000000000000000000000000000000000000000000000
                )

            [11] => sambapasswordhistory
            [sambaacctflags] => Array
                (
                    [count] => 1
                    [0] => [U          ]
                )

            [12] => sambaacctflags
            [shadowlastchange] => Array
                (
                    [count] => 1
                    [0] => 16207
                )

            [13] => shadowlastchange
            [sambapwdlastset] => Array
                (
                    [count] => 1
                    [0] => 1400916704
                )

            [14] => sambapwdlastset
            [travelling] => Array
                (
                    [count] => 1
                    [0] => TODAS
                )

            [15] => travelling
            [loginshell] => Array
                (
                    [count] => 1
                    [0] => /bin/basho
                )

            [16] => loginshell
            [pushover] => Array
                (
                    [count] => 1
                    [0] => uB3t2QaS9CUGDv2KRrboGRXodqJFTi
                )

            [17] => pushover
            [sambantpassword] => Array
                (
                    [count] => 1
                    [0] => 8727106223A0F6EBA30E147805F11F31
                )

            [18] => sambantpassword
            [licenciagoogle] => Array
                (
                    [count] => 1
                    [0] => Basic
                )

            [19] => licenciagoogle
            [uid] => Array
                (
                    [count] => 1
                    [0] => jferia
                )

            [20] => uid
            [givenname] => Array
                (
                    [count] => 1
                    [0] => Juan Pablo
                )

            [21] => givenname
            [sn] => Array
                (
                    [count] => 1
                    [0] => Feria
                )

            [22] => sn
            [mail] => Array
                (
                    [count] => 1
                    [0] => jferia@tpitic.com.mx
                )

            [23] => mail
            [aliascuentagoogle] => Array
                (
                    [count] => 5
                    [0] => jferia@transportespitic.com.mx
                    [1] => jferia@transportespitic.com
                    [2] => jferia@transportespitic.mx
                    [3] => jferia@tpitic.com
                    [4] => jferia@tpitic.mx
                )

            [24] => aliascuentagoogle
            [aliasgoogle] => Array
                (
                    [count] => 1
                    [0] => user1
                )

            [25] => aliasgoogle
            [userpassword] => Array
                (
                    [count] => 1
                    [0] => {SHA}RBagWOjVatC5hlhmMH69YVGuUso=
                )

            [26] => userpassword
            [noempleado] => Array
                (
                    [count] => 1
                    [0] => 10000
                )

            [27] => noempleado
            [cn] => Array
                (
                    [count] => 1
                    [0] => jferia
                )

            [28] => cn
            [puesto] => Array
                (
                    [count] => 1
                    [0] => no establecido
                )

            [29] => puesto
            [oficina] => Array
                (
                    [count] => 1
                    [0] => SIS
                )

            [30] => oficina
            [livemeeting] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [31] => livemeeting
            [feriacustom1] => Array
                (
                    [count] => 1
                    [0] => 4
                )

            [32] => feriacustom1
            [nivelscup] => Array
                (
                    [count] => 1
                    [0] => 1
                )

            [33] => nivelscup
            [deptorh] => Array
                (
                    [count] => 1
                    [0] => 0
                )

            [34] => deptorh
            [nivelrh] => Array
                (
                    [count] => 1
                    [0] => 0
                )

            [35] => nivelrh
            [lanip] => Array
                (
                    [count] => 1
                    [0] => 192.168.140.169
                )

            [36] => lanip
            [lanmac] => Array
                (
                    [count] => 1
                    [0] => F0:79:60:0F:3D:A7
                )

            [37] => lanmac
            [wifiip] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [38] => wifiip
            [wifimac] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [39] => wifimac
            [voicemac] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [40] => voicemac
            [voicemodel] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [41] => voicemodel
            [extension] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [42] => extension
            [voiceip] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [43] => voiceip
            [clavetelefono] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [44] => clavetelefono
            [urlvip] => Array
                (
                    [count] => 1
                    [0] => 1
                )

            [45] => urlvip
            [homedirectory] => Array
                (
                    [count] => 1
                    [0] => /home/sincorreo
                )

            [46] => homedirectory
            [correo] => Array
                (
                    [count] => 1
                    [0] => 0
                )

            [47] => correo
            [web] => Array
                (
                    [count] => 1
                    [0] => 1
                )

            [48] => web
            [jabber] => Array
                (
                    [count] => 1
                    [0] => 1
                )

            [49] => jabber
            [usomoviles] => Array
                (
                    [count] => 1
                    [0] => SI
                )

            [50] => usomoviles
            [office] => Array
                (
                    [count] => 1
                    [0] => 0
                )

            [51] => office
            [anfitrion] => Array
                (
                    [count] => 1
                    [0] => 1
                )

            [52] => anfitrion
            [servicios] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [53] => servicios
            [cobrador] => Array
                (
                    [count] => 1
                    [0] => 0
                )

            [54] => cobrador
            [chofer] => Array
                (
                    [count] => 1
                    [0] => 0
                )

            [55] => chofer
            [accesostemporales] => Array
                (
                    [count] => 1
                    [0] => NO
                )

            [56] => accesostemporales
            [accesosdered] => Array
                (
                    [count] => 1
                    [0] => 8
                )

            [57] => accesosdered
            [count] => 58
            [dn] => uid=jferia,ou=People,dc=transportespitic,dc=com
        )

)
*/

?>



