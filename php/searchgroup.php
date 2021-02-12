<?php
//$_POST["keyword"]='am';
//https://stackoverflow.com/questions/32197105/autocomplete-textbox-from-active-directory-usind-ldap-users-in-php
//error_reporting(0);
//echo '<pre>';
//print_r($matches);
//echo '</pre>';
if(!empty($_POST["keyword"])) {
      $vals = preg_match('/(\w+)(Por([u|n][s|o][e|m]))/', $_POST["keyword"], $matches, PREG_OFFSET_CAPTURE);
      $ldapserver = 'ldap.tpitic.com.mx';
      $ldapuser      = 'cn=feria,dc=transportespitic,dc=com';  
      $ldappass     = 'sistemaspitic';
      $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
      //$ldapfilter='';
      //$ldaptree='';
      if ($matches[2][0] == 'Pornom') {
            $ldapfilter="(cn=".$matches[1][0]."*)";
            $ldaptree    = "ou=TPiticGoogleAliases,ou=groups,dc=transportespitic,dc=com";
            //$ldaptree    = "ou=groups,dc=transportespitic,dc=com";
      }
      if ($matches[2][0] == 'Poruse') {
            $ldapfilter="(uid=".$matches[1][0]."*)";
            $ldaptree    = "ou=People,dc=transportespitic,dc=com";
      }

      //$ldapfilter="(cn=".$_POST["keyword"]."*)";
      $aut='';
      //echo $ldapfilter;
      if($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
            if ($ldapbind) {
                  $result = ldap_search($ldapconn,$ldaptree, $ldapfilter) or die ("Error in search query: ".ldap_error($ldapconn));
                  $ldata = ldap_get_entries($ldapconn, $result);
                  for ($i = 0; $i < $ldata["count"]; $i++) {
                        $return_arr[] =  $ldata[$i]["cn"][0];
                        if ($matches[2][0] == 'Pornom') {
                              $aut .= '<li> <a href="#" onClick="selectGroup('."'".$ldata[$i]["cn"][0]."'".','."'".$matches[2][0]."'".');" >'.$ldata[$i]["cn"][0].'</a></li>';
                        }
                        if ($matches[2][0] == 'Poruse') {
                              $aut .= '<li> <a href="#" onClick="selectGroup('."'".$ldata[$i]["uid"][0]."'".','."'".$matches[2][0]."'".');" >'.$ldata[$i]["uid"][0].'</a></li>';
                        }

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
  
