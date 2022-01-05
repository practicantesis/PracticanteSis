<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LDAP</title>
</head>

<body>
    <?php
    $ldap_dn = "cn=feria,dc=transportespitic,dc=com";
    $ldap_password = "sistemaspitic";

    $ldap_con = ldap_connect("ldap.tpitic.com.mx");

    ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

    /*  if(ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {

     echo "Bind successful!";
     
   } else {
       echo "Invalid user/pass or other errors!";
   }*/

    if (ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
        $filter = "(duusernname=aalcantar)";
        $result = ldap_search($ldap_con, "ou=DeviceUsers,dc=transportespitic,dc=com", $filter) or exit("busqueda no encontrada");
        $count = ldap_count_entries($ldap_con, $result); #cuenta el numero de arreglos dentro del array
        $entries = ldap_get_entries($ldap_con, $result);
        print "<pre>";
        print_r($entries);
        print "</pre>";
        print "<br>";
        for ($i = 0; $i < $count; $i++) {
            echo "resultado: ".$entries[$i]['duusernname'][0] . "<br>";
        }
    } else {
        echo "Invalid user/pass or other errors!";
    }
    ?>


    <?php


    ?>
</body>

</html>