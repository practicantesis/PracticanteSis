<?php
require('funciones.php');
//print_r($_POST);
//print_r($_SERVER);
$con=ConectaLDAP();
$user=$_POST['user'];
if (isset($_POST['src'])) {
	if ($_POST['src'] == "rp") {
		//echo "CAMBIANDO PASS";
		$filter = "(uid=".$_POST['user'].")";
	    $srch =ldap_search($con, "ou=People,dc=transportespitic,dc=com",$filter);
	    $numero=ldap_count_entries($con, $srch);
	    $info = ldap_get_entries($con, $srch);
	    $info[0]["userpassword"][0];
	    if ($numero != 0){
	        $logstate = validatePassword("tpitic",$info[0]["userpassword"][0]);
			if($logstate == "SI"){
				$html='<br><input type="password" name="passa" id="passa" class="form-control" placeholder="Password"><br><input type="password" name="passb" id="passb" placeholder="Confirme Password" class="form-control">';
				$jsonSearchResults[] =  array(
					'html' => $html,
					'passct' => "El Password es ".$pass,	
				    'success' => "ASKPASS",
				);
				echo json_encode ($jsonSearchResults);
				return false;
			}
			if($logstate == "NO"){
				$jsonSearchResults[] =  array(
				    'success' => "NOTRESETASK",
				);
				echo json_encode ($jsonSearchResults);
				return false;
			}


		} else {
			$jsonSearchResults[] =  array(
				'html' => $html,
				'passct' => "El Password es ".$pass,	
			    'success' => "NOEXISTE",
			);
			echo json_encode ($jsonSearchResults);
			return false;
		}
	}        
	if ($_POST['src'] == "ps") {
		if ($_POST['ps'] != $_POST['psb']) {
			$jsonSearchResults[] =  array(
			    'success' => "DONTMATCH"
			);
			echo json_encode ($jsonSearchResults);
			return false;

		} else {
			$password=$_POST['ps'];
			// Validate password strength
			$uppercase = preg_match('@[A-Z]@', $password);
			$lowercase = preg_match('@[a-z]@', $password);
			$number    = preg_match('@[0-9]@', $password);
			$specialChars = preg_match('@[^\w]@', $password);
			if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
				$jsonSearchResults[] =  array(
				    'success' => "WEAK"
				);
				echo json_encode ($jsonSearchResults);
				return false;
			}else{
    			//echo 'Strong password.';
    			$pass=$password;
			}			
		}
	}
} else {
	//echo "SOLICITANDO CAMBIO DESDE SISTEMAS";
	$pass="tpitic";
}
// #Ummagumma666


$userldap = "uid=".$user.",ou=People,dc=transportespitic,dc=com";
$tienesmb=CheckSMBServiceForUser($userldap);

//echo "el pass es $pass - SNB: $tienesmb ";

$shapass = "{SHA}".base64_encode(sha1($pass, TRUE));
//$update['userPassword']=$shapass;
$update['userpassword']=$shapass;

if (( $pass !="tpitic" ) and ($tienesmb != "NO")) {
	$sambaNTPassword = strtoupper(hash('md4', iconv('UTF-8','UTF-16LE',$pass)));
	$update['sambaNTPassword']=strtoupper(hash('md4', iconv('UTF-8','UTF-16LE',$pass)));
}	



//print_r($update);
$mod=ldap_modify($con,$userldap, $update);
$err=ldap_error($con);

if ( $pass =="tpitic" ) {
	$success=$err;
	$jsonSearchResults[] =  array(
		'passct' => $msg,
		'err' => $err,
	    'success' => $success,
	);
	echo json_encode ($jsonSearchResults);
	return false;
}

$connglobal=ConectaSQL('globaldb');
$mambo=ChkExistMamboUser($user,$connglobal);

if ($mambo == "SI") {
	$hash=hash_password($pass);
	$passmd=md5($pass);
	ChgPasswdMamboUser($_POST['user'],$passmd);
}	

//print_r($update);
//return $mod;

//return false;


//randomPassword();
$msg="Cambio de password solicitado";
$success='YES';

/*
if ( $pass !="tpitic" ) {
	ResetPasswordLDAP($_POST['user'],$pass);
	$msg="Cambio de password OK";
	$success='YES';
}
*/

$jsonSearchResults[] =  array(
	'passct' => $msg,
	'err' => $err,
    'success' => $success,
);
echo json_encode ($jsonSearchResults);
return false;
?>
