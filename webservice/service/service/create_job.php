<?php
require('../global/db.php');
require('../global/status_code.php');


// alpha version
/* -- database --
job
id	detail	how_to_fix	problem_inform	problem_actual	checkin_start	checkin_finish	pic	comment_group_id
*/

/* -- mode --
web_add
web_get_jobs
web_get_employees
web_assign_job
not set => via web
*/


$detail = $_POST[detail];
$how_to_fix = $_POST[how_to_fix];
$problem_inform = $_POST[problem_inform];
$problem_actual = $_POST[problem_actual];
$checkin_start = $_POST[checkin_start];
$checkin_finish = $_POST[checkin_finish];
//$pic = $_POST[pic];
$comment_group_id = $_POST[comment_group_id];

// check mode; call via mobile or web
$mode = $_POST[mode];

//$mode = "web_get_employees";

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
else if($mode == "mobile_edit"){
	mysql_query("UPDATE employee SET device_id = $device_id, username = '$username', password = '$password', name = '$name', lastname = '$lastname', tel = '$tel', email = '$email' 
WHERE name='$name' AND lastname='$lastname'");
}





// worked
else if($mode == "web_assign_job"){
	$job_id = $_POST[job_id];
	$employee_id = $_POST[employee_id];
	// check all parameter is not null
	if($job_id == "" || $employee_id == ""){		
		$result = array("status"=>$codes["406"], "result"=>"Please enter any job and employee");
		echo json_encode($result);
		exit();
	}

	$sql="INSERT INTO job_holder (job_id, employee_id)
VALUES
('$job_id', '$employee_id')";

	// if can't insert value to database
	if (!mysql_query($sql,$mysql_link))
	{
		$result = array("status"=>$codes["503"], "result"=>"Can't add this job holder");
		echo json_encode($result);
		exit();
	}

	// finish create new job
	$result = array("status"=>$codes["201"], "result"=>"Successful");
	echo json_encode($result);
}
else if($mode == "web_get_jobs"){
	echo json_encode(Get_Jobs($detail));
}
else if($mode == "web_get_employees"){
	$name = $_POST[name];
	$lastname = $_POST[lastname];
	echo json_encode(Get_Employees($name, $lastname));
}
else if($mode == "web_add"){
	// check all parameter is not null
	$comment_group_id = "0";
	if($detail == "" || $problem_inform == ""){		
		$result = array("status"=>$codes["406"], "result"=>"Please fill in all fields");
		echo json_encode($result);
		exit();
	}

	$sql="INSERT INTO job (datetime, detail, problem_inform, comment_group_id)
VALUES
(NOW(),'$detail','$problem_inform','$comment_group_id')";

	// if can't insert value to database
	if (!mysql_query($sql,$mysql_link))
	{
		$result = array("status"=>$codes["503"], "result"=>"Can't add this item");
		echo json_encode($result);
		exit();
	}

	// finish create new job
	$result = array("status"=>$codes["201"], "result"=>"Successful");
	echo json_encode($result);
}
// when finish all work try to close database connection
mysql_close($mysql_link);


// function
function Get_Jobs($filter_detail){
require('../global/status_code.php');
	$jobs = array();
	
	$result = mysql_query("select * from job where detail like '%$filter_detail%'"); 
	$num = mysql_num_rows($result);
	
	if($num <=0) {
		// not found any job
		$jobs = array("status"=>$codes["404"], "result"=>"Not found any job");
	}	
	else {	
		while($row = mysql_fetch_array($result))
		{
			// found job(s)		
			$result_job = array('id'=>$row['id']
			, 'detail'=>$row['detail']
			, 'how_to_fix'=>$row['how_to_fix']
			, 'problem_inform'=>$row['problem_inform']
			, 'problem_actual'=>$row['problem_actual']
			, 'checkin_start'=>$row['checkin_finish']
			, 'pic'=>$row['pic']
			, 'comment_group_id'=>$row['comment_group_id']);
			array_push($jobs, $result_job);
		}
		$jobs = array("status"=>$codes["200"], "result"=>$jobs);
	}
	return $jobs;
}
function Get_Employees($name_para, $lastname_para){
require('../global/status_code.php');
	
	if($name_para == "" && $lastname_para == ""){
	$employees = array("status"=>$codes["406"], "result"=>"Plaese enter name or lastname");
	}else{
	$result = mysql_query("select * from employee where name like '%$name_para%' and lastname like '%$lastname_para%'"); 
	$num = mysql_num_rows($result);
	
	if($num <=0) {
		$employees = array("status"=>$codes["404"], "result"=>"Not found any employee");
	}	
	else {	
	$employees = array();
		while($row = mysql_fetch_array($result))
		{
			// found employee		
			$result_emp = array('id'=>$row['id']
			, 'name'=>$row['name']
			, 'lastname'=>$row['lastname']);
			array_push($employees, $result_emp);
		}
		$employees = array("status"=>$codes["200"], "result"=>$employees);
	}
	}
	return $employees;
}
?>