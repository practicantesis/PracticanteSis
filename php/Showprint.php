<?php
$err='';
$data='.';
set_time_limit(30);
//error_reporting(E_ALL);
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors',1);
error_reporting(0);
ini_set('display_errors', 0);

$ldapserver = 'ldap.tpitic.com.mx';
$ldapuser      = 'cn=feria,dc=transportespitic,dc=com';  
$ldappass     = 'sistemaspitic';
$ldaptree    = "ou=Impresoras,ou=Devices,dc=transportespitic,dc=com";

$ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");



$data.='
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    <h4>Impresoras</h4>';
                                $data.='</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="teibol" name="teibol">
                                        <thead>
                                            <tr>
                                                <th>Tag</th>
                                                <th>IP</th>
                                                <th>Uptime</th>
                                                <th>Modelo/Serie</th>
                                                <th>Toner</th>
                                            </tr>
                                        </thead>
                                        <tbody>';


if($ldapconn) {
    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
    if ($ldapbind) {
        //echo "LDAP bind successful...<br /><br />";
        $result = ldap_search($ldapconn,$ldaptree, "(DeviceTAG=*)") or die ("Error in search query: ".ldap_error($ldapconn));
        $ldata = ldap_get_entries($ldapconn, $result);
        for ($i=0; $i<$ldata["count"]; $i++) {
	$ping = exec("ping -w 1 -c 1 -s 64 -t 64 ".$ldata[$i]["deviceip"][0]);
            //echo "dn is: ". $data[$i]["dn"] ."<br />";
            //$data.= "<br>User $i : ". $ldata[$i]["devicetag"][0] ."<br />";
	    if ($ping) {
            $testSN=snmpget($ldata[$i]["deviceip"][0],'public', 'SNMPv2-SMI::mib-2.43.11.1.1.8.1.1',100000,2);
            if ($testSN) {
                $total=str_replace('INTEGER:','',snmpget($ldata[$i]["deviceip"][0],'public', 'SNMPv2-SMI::mib-2.43.11.1.1.8.1.1'));
                $resto=str_replace('INTEGER:','',snmpget($ldata[$i]["deviceip"][0],'public', 'SNMPv2-SMI::mib-2.43.11.1.1.9.1.1'));
                $serie=str_replace('"','',str_replace('STRING:','',snmpget($ldata[$i]["deviceip"][0],'public', 'SNMPv2-SMI::mib-2.43.5.1.1.17.1')));
                $modelo=str_replace('"','',str_replace('STRING:','',snmpget($ldata[$i]["deviceip"][0],'public', 'SNMPv2-SMI::mib-2.43.5.1.1.16.1'))); 
                $toner=(($resto*100)/$total);
                if ($toner <  30){
                    $toneri='<span class="badge badge-danger px-2">'.$toner.'%</span>';
                }
                if ($toner >  30){
                    $toneri='<span class="badge badge-success px-2">'.$toner.'%</span>';
                }
                $patron='/Timeticks\: \(\d+\)/';
		$up=preg_replace($patron,'',snmpget($ldata[$i]["deviceip"][0],'public', 'DISMAN-EVENT-MIB::sysUpTimeInstance'));
/*
                $data.='<tr>
                <td>'.$ldata[$i]["devicetag"][0].'</td>
                <td>'.$ldata[$i]["deviceip"][0].'</td>
                <td>'.preg_replace($patron,'',snmpget($ldata[$i]["deviceip"][0],'public', 'DISMAN-EVENT-MIB::sysUpTimeInstance')).'</td><td>';
                $data.=$modelo."<br>".$serie.'</td>
                <td>'.$toneri.'</td>
                </tr>';
*/
		$data.='<tr>';
		$data.='<td>'.$ldata[$i]["devicetag"][0].'</td>';
		$data.='<td>'.$ldata[$i]["deviceip"][0].'</td>';
		$data.='<td>'.$up.'</td>';
		$data.='<td>'.$modelo."<br>".$serie.'</td>';
		$data.='<td>'.$toneri.'</td></tr>';

            } else {
                $errcon .= "ERROR SNMP: ".$ldata[$i]["deviceip"][0]."\n";
            }
	    } else {
		$errcon .= "ERROR IP: ".$ldata[$i]["deviceip"][0]."\n";
	    }
        }
    } else {
        //echo "LDAP bind failed...";
    }

}

// all done? clean up
ldap_close($ldapconn);




                                    $data.='
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
';





$jsonSearchResults[] =  array(
    'success' => 'YES',
    'data' => $data,
    'error' => $err,
    'errorconn' => $errcon,
);
echo json_encode ($jsonSearchResults);

return false;

?>



