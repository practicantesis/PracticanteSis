<?php
//$_POST["keyword"]='am';
//https://stackoverflow.com/questions/32197105/autocomplete-textbox-from-active-directory-usind-ldap-users-in-php
if(!empty($_POST["keyword"])) {
      $ldapserver = 'ldap.tpitic.com.mx';
      $ldapuser      = 'cn=feria,dc=transportespitic,dc=com';  
      $ldappass     = 'sistemaspitic';
      $ldaptree    = "ou=DeviceUsers,dc=transportespitic,dc=com";
      $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
      //$ldapfilter="(uid=".$_POST["keyword"]."*)";
      //$ldapfilter="(duusernname=".$_POST["keyword"]."*)";
      $ldapfilter="(|(dunombre=".$_POST["keyword"]."*)(duusernname=".$_POST["keyword"]."*))";
/*
      $ldapfilter="(|(dunombre=".$_POST["keyword"]."*)(dunumeroempleado=".$_POST["keyword"]."*)(duusernname=".$_POST["keyword"]."*)(duoficina=".$_POST["keyword"]."))";
      $aut='';

      if (preg_match("/^[A-Z]{2,4}$/i",$_POST["keyword"],$matches)) {
            $ldapfilter="(oficina=".$_POST["keyword"].")";
            //echo $ldapfilter;
      }
*/      
      //echo $ldapfilter;
      if($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
            if ($ldapbind) {
                  $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
                  $ldata = ldap_get_entries($ldapconn, $result);
                  for ($i = 0; $i < $ldata["count"]; $i++) {
                        
                        //$aut .= '<li> <a href="#" onClick="selectUser('."'".$ldata[$i]["uid"][0]."'".');" >'.$ldata[$i]["uid"][0].'</a></li>';
                        //if (isset($ldata[$i]["uid"][0])) {
                              $return_arr[] =  $ldata[$i]["duusernname"][0];
                              $aut .= '<li> <a href="#" onClick="selectDevUser('."'".$ldata[$i]["duusernname"][0]."'".');" >'.$ldata[$i]["duusernname"][0].' - '.$ldata[$i]["dunumeroempleado"][0].' '.$ldata[$i]["dunombre"][0].' ('.$ldata[$i]["duoficina"][0].') </a></li>';
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
                        echo "No matches found! $ldapfilter ";
                  }
            }
      }
      //echo json_encode($return_arr);
      echo $aut;
}

?>
  
