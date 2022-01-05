<?php
//$_POST["keyword"]='am';
//https://stackoverflow.com/questions/32197105/autocomplete-textbox-from-active-directory-usind-ldap-users-in-php

require('funciones.php');

//print_r($_POST);

$FOUND="DUNNO";

$mes.='<div>.</div>';
if (preg_match("/^[a-zA-Z]+$/i",$_POST["param"],$matches)) {
      //$mes.='<div>-</div>';
      $di=GetDeviceUserInfoFromLDAP($_POST["param"]);
      if ($di['count'] == 0) {
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" DEVICE USER NO ENCONTRADO EN LDAP";
            $mes.='</div>';
      }
      if ($di['count'] > 1) {
            $mes .='<div class="alert alert-success" role="alert">';            
            $mes.=" DEVICE USER DUPLICADO, SE ENCUENTRA ".$di['count']." VECES EN LDAP";
            $mes.='</div>';
            $FOUND="YES";
      }
      if ($di['count'] == 1) {
            $mes .='<div class="alert alert-success" role="alert">';
            $mes.=" DEVICE USER ENCONTRADO EN LDAP";
            $mes.='</div>';
            $FOUND="YES";
            $luser=GetDeviceTagInfoFromAssignedUserLDAP($_POST["param"]);
            //print_r($luser);
            if ($luser['count'] == 1) {
                  $mes .='<div class="alert alert-success" role="alert">';
                  $mes.=" EL DEV USER TIENE DISPOSITIVO ASIGNADO EN LDAP ".$luser[0]['devicetag'][0];
                  $mes.='</div>';
            }

            if ($luser['count'] == 0) {
                  $mes .='<div class="alert alert-danger" role="alert">';
                  $mes.=" EL DEV USER NO TIENE DISPOSITIVO ASIGNADO EN LDAP";
                  $mes.='</div>';
            }




      }            
//print_r($di);
}      

if (preg_match("/^CEL(\w\w\w)\d+$/i",$_POST["param"],$matches)) {
      //$mes.='<div>-</div>';
      $mes .='<div class="alert alert-success" role="alert">';
      $mes .=  "TAG Valido para OFICINA ".$matches[1];
      $mes.='</div>';
      $ofi =  $matches[1];
      $di=GetDeviceInfoFromLDAP("ou=Celulares,ou=Devices,dc=transportespitic,dc=com","devicetag",$_POST["param"]);
      if ($di['count'] == 0) {
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" TAG NO ENCONTRADO EN LDAP";
            $mes.='</div>';
      }
      if ($di['count'] > 1) {
            $mes.=" TAG DUPLICADO, SE ENCUENTRA ".$di['count']." VECES EN LDAP";
      }
      if ($di['count'] == 1) {
            $mes .='<div class="alert alert-success" role="alert">';
            $mes.=" TAG ENCONTRADO EN LDAP";
            $mes.='</div>';
            if (preg_match("/^BAJA_CEL_(\w\w\w)$/i",$di[0]['deviceoffice'][0],$matchesb)) {
                  $mes.='<div class="alert alert-danger" role="alert">';
                  $mes.=" DEVICE DADO DE BAJA EN LDAP REGION ".$matchesb[1];
                  $mes.='</div>';
            } else {
                  $mes .='<div class="alert alert-success" role="alert">';
                  $mes.=" DEVICE EXISTENTE EN LDAP PERETENECE A OFICINA ".$di[0]['deviceoffice'][0]." <br>IMEI LDAP: ".$di[0]['deviceimei'][0]." <br>SERIAL LDAP: ".$di[0]['deviceserial'][0] ;
                  $mes.='</div>';
                  // CHECK ASSIGNED USER
                  $ato=GetDeviceUserInfoFromLDAP($di[0]['deviceassignedto'][0]);
                  //print_r($ato);
                  if ($ato['count'] == 0) {
                        $mes.='<div class="alert alert-danger" role="alert">';
                        $mes.=" DEVICE USER DECLARADO PARA CELULAR ".$di[0]['deviceassignedto'][0]." NO ENCONTRADO EN LDAP";
                        $mes.='</div>';
                  }
                  if ($ato['count'] > 1) {
                        $mes .='<div class="alert alert-success" role="alert">';            
                        $mes.=" DEVICE USER DECLARADO PARA CELULAR ".$di[0]['deviceassignedto'][0]." DUPLICADO, SE ENCUENTRA ".$ato['count']." VECES EN LDAP";
                        $mes.='</div>';
                        $FOUND="YES";
                  }
                  if ($ato['count'] == 1) {
                        $mes .='<div class="alert alert-success" role="alert">';
                        $mes.=" DEVICE USER DECLARADO PARA CELULAR ".$di[0]['deviceassignedto'][0]." ENCONTRADO EN LDAP";
                        $mes.='</div>';
                        $FOUND="YES";
                  }            
                  // CHECK serial for TAG ON AIRWATCH
                  $airwatchPorSerie=QueryToAirwatchAPI('DEVICE',$di[0]['deviceserial'][0]);
                  //echo $airwatchPorSerie;
                  //
                  $eljson = json_decode ($airwatchPorSerie, true);
                  //echo $Friendlyname=$eljson['DeviceFriendlyName'];
                  //print_r($eljson);
                  if ($eljson == "404") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" NUMERO DE SERIE ".$di[0]['deviceserial'][0]." NO ENCONTRADO EN AIRWATCH ";
                        $mes.='</div>';
                        $FOUND="AW404";

                  } else {
                        if ($_POST["param"] == $eljson['DeviceFriendlyName']) {
                              $mes .='<div class="alert alert-success" role="alert">';
                              $mes.=" NUMERO DE SERIE ENCONTRADO EN AIRWATCH Y COINCIDE EL DeviceFriendlyName CON EL TAG ";
                              $mes.='</div>';
                              $FOUND="AWYES";
                        } else {
                              $mes .='<div class="alert alert-danger" role="alert">';
                              $mes.=" NUMERO DE SERIE ENCONTRADO EN AIRWATCH PERO NO COINCIDE EL DeviceFriendlyName CON EL TAG ";
                              $mes.='</div>';
                              $FOUND="AWPEND";
                        }
                  }
                  // CHECK imei for TAG ON AIRWATCH
                  $airwatchPorImei=QueryToAirwatchAPI('DEVICEperIMEI',$di[0]['deviceimei'][0]);
                  $eljsoni = json_decode ($airwatchPorImei, true);
                  if ($eljsoni == "404") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" IMEI ".$di[0]['deviceimei'][0]." NO ENCONTRADO EN AIRWATCH ";
                        $mes.='</div>';
                        $FOUND="AWI404";

                  } else {
                        if ($_POST["param"] == $eljsoni['DeviceFriendlyName']) {
                              $mes .='<div class="alert alert-success" role="alert">';
                              $mes.=" IMEI ENCONTRADO EN AIRWATCH Y COINCIDE EL DeviceFriendlyName CON EL TAG ";
                              $mes.='</div>';
                              $FOUND="AWYES";
                        } else {
                              $mes .='<div class="alert alert-danger" role="alert">';
                              $mes.=" IMEI ENCONTRADO EN AIRWATCH PERO NO COINCIDE EL DeviceFriendlyName CON EL TAG ";
                              $mes.='</div>';
                              $FOUND="AWPEND";
                        }
                  }
                  // GetOCSTAG($serial,$conn)
                  // GetOCSHwIDFromIMEI($imei,$conn) {
                  // GetOCSImeiFromTag($tag,$conn) {
                  // GetOCSInfoFromTAG($tag,$what,$conn) 
                  // 
                  //
                  $conn=ConectaSQL('ocsweb');
                  // OCS SERIAL
                  //
                  $ocsserial=GetOCSTAG($di[0]['deviceserial'][0],$conn);
                  if ($ocsserial == "NOTFOUND") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" SERIE DECLARADA EN LDAP ".$di[0]['deviceserial'][0]." PARA DEVICE NO EXISTE EN OCS ";
                        $mes.='</div>';
                        $FOUND="OCSNOSERIE";
                  }
                  if ($ocsserial == $_POST["param"]) {
                        $mes .='<div class="alert alert-success" role="alert">';
                        $mes.=" SERIE DECLARADA EN LDAP ".$di[0]['deviceserial'][0]." PARA DEVICE SI EXISTE EN OCS ";
                        $mes.='</div>';
                        $FOUND="OCSNOSERIE";
                  }
                  // OCS OMEI
                  //echo $ocsimei=GetOCSImeiFromTag($_POST["param"],$conn);
                  $ocshw=GetOCSHwIDFromIMEI($di[0]['deviceimei'][0],$conn);
                  if ($ocshw == "NOTFOUND_or_DUP") {
                        $mes .='<div class="alert alert-danger" role="alert">';
                        $mes.=" IMEI NO ENCONTRADO O DUPLICADO EN OCS  ";
                        $mes.='</div>';
                        $FOUND="OCSIMEINOTFOUND";
                  } else {
                        $ocstg=GetOCSTAGFromHwId($ocshw,$conn);
                        if ($_POST["param"] == $ocstg) {
                              $mes .='<div class="alert alert-success" role="alert">';
                              $mes.=" IMEI ENCONTRADO EN OCS Y COINCIDE EL CON EL IMEI DE LDAP ";
                              $mes.='</div>';
                              $FOUND="OCSIMEIYES";
                        } else {
                              $mes .='<div class="alert alert-danger" role="alert">';
                              $mes.=" IMEI ENCONTRADO EN OCS PERO NO COINCIDE CON EL IMEI DE LDAP ";
                              $mes.='</div>';
                              $FOUND="OCSWRONMGIMEI";
                        }
                  }



            }                 
      }
      //print_r($di);
} else {
      if ($FOUND != "YES") {
            $mes.='<div class="alert alert-danger" role="alert">';
            $mes.=" NO VEO TAG NI DEVUSER CON ESO QUE PUSISTE, DE CUAL FUMASTE?";
            $mes.='</div>';
      }
}

$jsonSearchResults[] =  array(
    'success' => 'YES',
    'mes' => $mes,
    'ofi' => $ofi,
);
echo json_encode ($jsonSearchResults);

return false;




return false;

if(!empty($_POST["keyword"])) {
      $ldapserver = 'ldap.tpitic.com.mx';
      $ldapuser      = 'cn=feria,dc=transportespitic,dc=com';  
      $ldappass     = 'sistemaspitic';
      $ldaptree    = "ou=People,dc=transportespitic,dc=com";
      $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
      //$ldapfilter="(uid=".$_POST["keyword"]."*)";

      $ldapfilter="(|(uid=".$_POST["keyword"]."*)(sn=".$_POST["keyword"]."*)(givenname=".$_POST["keyword"]."*)(puesto=".$_POST["keyword"]."))";
      $aut='';


      if (strlen($_POST["keyword"]) == 17) {
            $_POST["keyword"]=strtoupper($_POST["keyword"]);
            echo $_POST["keyword"];
            if (filter_var($_POST["keyword"] , FILTER_VALIDATE_MAC)) {
                  $ldapfilter="(|(lanmac=".$_POST["keyword"].")(wifimac=".$_POST["keyword"]."))";
            }
      }
      if (filter_var($_POST["keyword"] , FILTER_VALIDATE_IP)) {
            $ldapfilter="(|(lanip=".$_POST["keyword"].")(voiceip=".$_POST["keyword"].")(DeviceIP=".$_POST["keyword"]."))";
            $ldaptree    = "dc=transportespitic,dc=com";

      }

      if (preg_match("/^192\.168\.\d+\.$/i",$_POST["keyword"],$matches)) {
            $ldapfilter="(|(lanip=".$_POST["keyword"]."*)(voiceip=".$_POST["keyword"]."*))";
            //echo $ldapfilter;
      }


      if (preg_match("/^[A-Z]{2,4}$/i",$_POST["keyword"],$matches)) {
            $ldapfilter="(oficina=".$_POST["keyword"].")";
            //echo $ldapfilter;
      }



//echo $ldapfilter;


      if($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
            if ($ldapbind) {
                  $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
                  $ldata = ldap_get_entries($ldapconn, $result);
                  for ($i = 0; $i < $ldata["count"]; $i++) {
                        
                        //$aut .= '<li> <a href="#" onClick="selectUser('."'".$ldata[$i]["uid"][0]."'".');" >'.$ldata[$i]["uid"][0].'</a></li>';
                        //if (isset($ldata[$i]["uid"][0])) {
                              $return_arr[] =  $ldata[$i]["uid"][0];
                              $aut .= '<li> <a href="#" onClick="selectUser('."'".$ldata[$i]["uid"][0]."'".');" >'.$ldata[$i]["uid"][0].' - '.$ldata[$i]["givenname"][0].' '.$ldata[$i]["sn"][0].' ('.$ldata[$i]["oficina"][0].') </a></li>';
                        //} else {
                        //      $aut .= '<li>xx</li>';
                        //}
                        //echo "<pre>";
                        //print_r($ldata);
                        //echo $ldata["count"];
                        //echo  $ldata[$i]["uid"][0];
                        //echo "</pre>";
                  }
                  if ($i == 0) {
                        echo "No matches found!";
                  }
            }
      }
      //echo json_encode($return_arr);
      echo $aut;
}

?>
  
