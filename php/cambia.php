<?php 
$conn = new mysqli('mysql', 'adminusertpitic', 'adminusertpitic', 'globaldb');
if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
} 

$query="update only_users set password = '".md5($_POST['ps'])."' where username = '".$_POST['us']."' ";
$result = mysqli_query($conn, $query);
$ar=mysqli_affected_rows($conn);
if ($conn->query($query) === TRUE) {
	if ($ar > 0 ) {
		$success="YES";
	} else {
		$success="NO";
		$err="User not found or same pass";
	}
} else {
	$err=mysqli_error($conn);
	$success="NO";
}

$jsonSearchResults[] =  array(
	'success' => $success,
	'error' => $err,
);
echo json_encode ($jsonSearchResults);

return false;
	
?>
