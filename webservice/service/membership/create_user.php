<?php
require('../global/db.php');
require('../global/status_code.php');

/* -- database --
employee
id	username	password	name	lastname	position_id	tel	email	is_active	device_id	account_type
*/

/* -- mode --
mobile_reg
mobile_get_info
mobile_edit
not set => regist via web
*/

$username = $_POST[username];
$password = $_POST[password];
$name = $_POST[name];
$lastname = $_POST[lastname];
$position_id = $_POST[position_id];
$tel = $_POST[tel];
$email = $_POST[email];
$is_active = "0";
$device_id = $_POST[device_id];
$account_type = $_POST[account_type];
$security_code = random_gen(8);
$mac_addr = $_POST[mac_addr]; // only on mobile mode
$os_ver = $_POST[os_ver]; // only on mobile mode

// check mode; call via mobile or web
$mode = $_POST[mode];

if($mode == "mobile_reg"){

	// create new device
	$sql="INSERT INTO device (mac_addr, os_ver)
VALUES
('$mac_addr','$os_ver')";

	// if can't insert value to database
	if (!mysql_query($sql,$mysql_link))
	{
		$result = array("status"=>$codes["503"], "result"=>"Can't register this device");
		echo json_encode($result);
		exit();
	}
	
	$device_id = mysql_insert_id();
	mysql_query("UPDATE employee SET device_id = $device_id , is_active = 1
WHERE name='$name' AND lastname='$lastname'");
}
else if($mode == "mobile_get_info"){
	// check already username in database
	$result = mysql_query("select * from employee where name ='".$name."', lastname = '".$lastname."'"); 
	$num = mysql_num_rows($result);
	//if not found name in database
	
	if($num <=0) {
		// this user is not found
		$result = array("status"=>$codes["404"], "result"=>"Not found your name");
		echo json_encode($result);
		// exit();
	}	
	else {	
		while($row = mysql_fetch_array($result))
		{
			// found user		
			$result = array("status"=>$codes["200"], "result"=>array('id'=>$row['id']
			, 'username'=>$row['username']
			, 'password'=>$row['password']
			, 'name'=>$row['name']
			, 'lastname'=>$row['lastname']
			, 'tel'=>$row['tel']
			, 'email'=>$row['email']));
			echo json_encode($result);
			break;
		}
	}
}
else if($mode == "mobile_edit"){
	mysql_query("UPDATE employee SET device_id = $device_id, username = '$username', password = '$password', name = '$name', lastname = '$lastname', tel = '$tel', email = '$email' 
WHERE name='$name' AND lastname='$lastname'");
}
else{
	// check already username in database
	$result = mysql_query("select * from employee where username ='".$username."'"); 
	$num = mysql_num_rows($result);
	//if there are already the username,then exit
	if($num >0) {
		$result = array("status"=>$codes["406"], "result"=>"This username is already exists");
		echo json_encode($result);
		exit();
	}
	
	// check all parameter is not null
	if($username == "" || $password == "" || $name == "" || $lastname == "" ||
			$position_id == "" || $tel == "" || $email == "" || $is_active == "" ||
			$device_id == "" || $account_type == ""){
		
		$result = array("status"=>$codes["406"], "result"=>"Please fill in all fields");
		echo json_encode($result);
		exit();
	}

	// if can use the username
	$sql="INSERT INTO employee (username, password, name, lastname, position_id, tel, email, is_active, device_id, account_type, security_code)
VALUES
('$username','$password','$name','$lastname',$position_id,'$tel','$email',$is_active,$device_id,$account_type,'$security_code')";

	// if can't insert value to database
	if (!mysql_query($sql,$mysql_link))
	{
		$result = array("status"=>$codes["503"], "result"=>"Can't register this user");
		echo json_encode($result);
		exit();
	}

	// finish create new employee
	$result = array("status"=>$codes["201"], "result"=>"Successful");
	echo json_encode($result);

	mysql_close($mysql_link);
}

function random_gen($length)
{
	require('../global/status_code.php');
	$random= "";
	srand((double)microtime()*1000000);
	// $char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	// $char_list .= "abcdefghijklmnopqrstuvwxyz";
	$char_list = "1234567890";
	// Add the special characters to $char_list if needed

	for($i = 0; $i < $length; $i++)  
	{    
		$random .= substr($char_list,(rand()%(strlen($char_list))), 1);  
	}  
	return $random;
} 
?>