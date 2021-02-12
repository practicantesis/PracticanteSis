<?php
//ini_set('display_errors',0);
require('funciones.php');
$html='';

$list=array();
if ($_POST['tipo'] == "Poruse") {
    $list=GetGroupsForUser($_POST['group'],"array");    
    //print_r($list);
    $cons=0;
    $fulluser="uid=".$_POST['group'].",ou=People,dc=transportespitic,dc=com";
    foreach ($list as &$valor) {
        $cons++;
        $html.='<div class="input-group">';
       $html.='<input type="text" class="form-control" value="'.$valor.'" aria-label="Text input with dropdown button">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accion</button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#" onClick="DelUserFromGroup('."'".$fulluser."'".','."'".$valor."'".','."'".$cons."'".');">Eliminar User Del Grupo </a>
            </div>
          </div><br>';
          $html.='</div>';
    }
//function DelUserFromGroup(value,grupo,indice) {

}

if ($_POST['tipo'] == "Pornom") {
    $list=GetGroupMembers($_POST['group'],"array","groupname");    
    $listm=GetGroupMembers($_POST['group'],"array","groupmembers");    
    //print_r($list);
    $cn=1;
    $html.='<div class="card-columns"><div class="card"><div class="card-body">';
    foreach ($list as &$valor) {
        $html.='<div class="input-group">';
       $html.='<input type="text" id="SelectedGroup" class="form-control" value="'.$valor.'" aria-label="Text input with dropdown button">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acciones</button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#" onClick="AddUserToGroup('."'".$valor."'".','."'".$_POST['group']."'".','."'".$cn."'".');">Agregar Usuario al Grupo</a>
            </div>
          </div> <br><div class="card"><div class="card-body"><div id="CapturaNUser'.$cn.'"></div></div></div><br>';


        $html.='</div>';

        $html.='<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample'.$cn.'" aria-expanded="false" aria-controls="collapseExample'.$cn.'">Ver Miembros</button><div class="collapse" id="collapseExample'.$cn.'"><div class="card card-body">';

                foreach ($listm as &$valorm) {
                   $html.='<div class="input-group input-group-sm mb-3">';
                   $html.='<input type="text" class="form-control" value="'.$valorm.'" aria-label="Text input with dropdown button">
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acciones</button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="#" onClick="DelUserFromGroup('."'".$valorm."'".','."'".$valor."'".','."'".$cn."'".');">Eliminar Del Grupo</a>
                        </div>
                      </div><br>';
                      $html.='</div>';
                }

        $html.='</div></div>';                
        $cn++;
    }
    $html.='</div"></div"></div">';


}


//echo $html;
//print_r($_POST);


$jsonSearchResults[] =  array(
    'data' => $html,
    //'error' => $err,
    'success' => "YES"
);
echo json_encode ($jsonSearchResults);


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




