<?php
require('../global/db.php');

$username = $_POST[username];
$password = $_POST[password];
$result = mysql_query("select * from employee where username ='".$username."' and password ='".$password."'"); 
$num = mysql_num_rows($result);
if($num <=0) {
	// this user is not authorized
	$result = array("result"=>"not authorized");
	echo json_encode($result);
}	
else {	
	while($row = mysql_fetch_array($result))
	{
		// this user is authorized
		$result = array("result"=>"authorized");
		echo json_encode($result);
		break;
	}
}
mysql_close($mysql_link)
?>