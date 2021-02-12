<?php
ini_set('display_errors',0);
require('funciones.php');
$list=array();
echo $list=GetUsersFromLDAP("dc=transportespitic,dc=com","htmltable");



return false;

//print_r($list);
$modelo=$list[0]['devicemodel'][0];
$marca=$list[0]['devicebrand'][0];
$ip=$list[0]['deviceip'][0];
if ($marca == "AVAYA") {
    $success='NO';
    $err='TIRAR ESE AVAYA A LA BASURA';
}
if ($marca == "CISCO") {
    switch ($modelo) {
        case "NO DEFINIDO":
            $success='NO';
            $err='DATOS NO REGISTRADOS';           
            break;
        case "SF 200-24P":
            $success='NO';
            $err='SIN SOPORTE SNMP'; 
            break;
        case "SG220-50P":
            $success='SABE';
            //$first=49;
            break;
        case "SG200-50P":
            $success='SABE';
            $first=49;
            break;
        case "SG200-50FP":
            $success='SABE';
           $first=49;
            break;
        case "SG220-26P":
            $success='SABE';
            break;
        case "SG220-26P":
            $success='SABE';
            break;
        case "SG220-26P":
            $success='SABE';
            break;
        case "SG200-26P":
            $success='SABE';
	    $first=49;
            break;
        case "SF200-24P":
            $success='SABE';
            break;
        case "SF300-24PP":
            $success='SABE';
            break;
        case "SG 200-08":
            $success='NO';
            $err='SIN SOPORTE SNMP'; 

            break;
        case 2:
        
            echo "i es igual a 2";
            break;


    default;

$jsonSearchResults[] =  array(
    'marca' => 'MODELO NO REGISTRADO',
    'ip' => 'MODELO NO REGISTRADO',
    'modelo' => 'MODELO NO REGISTRADO',
    'success' => 'YES',
    'data' => 'MODELO NO REGISTRADO',
    'error' => $err,
);
echo json_encode ($jsonSearchResults);


return false;

    break;

    }
}
if ($success == "SABE") {
    $name=snmpget($ip,'public', 'SNMPv2-MIB::sysName.0');
    $le=error_get_last();
    if(preg_match("/No response/",$le[message])) {
    //if($name) {    
        $success='NO';
        $err='NO HAY RESPUESTA';
    } else {
        $success='YES';
        $err='SI PASO';
        if(preg_match("/SW(\d+)/",$_POST['where'],$nopuertos)) {
            $nopuertos[1];

        }
        $sysdesc=str_replace('STRING:','',snmpget($ip,'public', 'SNMPv2-MIB::sysDescr.0'));
        $up=str_replace('STRING:','',snmpget($ip,'public', 'DISMAN-EVENT-MIB::sysUpTimeInstance'));
        $con=str_replace('STRING:','',snmpget($ip,'public', 'SNMPv2-MIB::sysContact.0'));
        $hostname=str_replace('STRING:','',snmpget($ip,'public', 'SNMPv2-MIB::sysName.0'));        
        $location=str_replace('STRING:','',snmpget($ip,'public', 'SNMPv2-MIB::sysLocation.0'));
        $data='             <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header"><h4>Sistema: '.$_POST['what'].' -> '.$_POST['where'].'</h4>
                                        <div class="card-body">
                                            <blockquote class="blockquote mb-0">
                                                    <p>';
                                                        $data.='<br><b>System IP: </b> '.$ip.'<br>';
                                                        $data.='<br><b>System Name:</b> '.$name; 
                                                        $data.='<br><b>System Description:</b> '.$sysdesc;
                                                        $data.='<br><b>System Up Time:</b> '.$up;
                                                        $data.='<br><b>System Contact:</b> '.$con;
                                                        $data.='<br><b>Hostname:</b> '.$hostname;
                                                        $data.='<br><b>Location:</b> '.$location;
                                                        $data.='</p>
                                                    <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                            </div>';
        $data.='            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h4>Puertos</h4>';
                                        $data.='</div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="teibol" name="teibol">
                                                <thead>
                                                    <tr>
                                                        <th>Puerto</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                for($a=$first;$a<($first+$nopuertos[1]);$a++) {
                                                    $techo = snmpget($ip,'public', "IF-MIB::ifDescr.".$a);
                                                    $desc=str_replace('STRING:','',$techo);
                                                    $techo = snmpget($ip,'public', "IF-MIB::ifOperStatus.".$a);
                                                    $techob=str_replace('INTEGER:','',$techo);
                                                    $techoc=str_replace('(2)','',$techob);
                                                    $stat=str_replace('(1)','',$techoc);
                                                    if ($stat == ' down'){
                                                        $btn='<span class="badge badge-danger px-2">DOWN!</span>';
                                                    }
                                                    if ($stat == ' up'){
                                                        $btn='<span class="badge badge-success px-2">UP</span>';
                                                    }
                                                    $data.='<tr>
                                                                <th>'.$desc.'</th>
                                                                <td>';
                                                                $data.=$btn;
                                                                $data.='</td>
                                                            </tr>';
                                                }
                                                $data.='
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
        ';
    }

}




$jsonSearchResults[] =  array(
    'marca' => $marca,
    'ip' => $ip,
    'modelo' => $modelo,
    'success' => $success,
    'data' => $data,
    'error' => $err,
);
echo json_encode ($jsonSearchResults);

//echo "<pre>";
//print_r($list);
//echo "</pre>";

return false;



?>




