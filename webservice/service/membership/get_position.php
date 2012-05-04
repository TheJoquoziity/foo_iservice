<?php
require('../global/db.php');
require('../global/status_code.php');

// return value in database
$sql = mysql_query("select * from employee_position"); 
$num = mysql_num_rows($sql);
if($num <=0) {
	$result = array("status"=>$codes["204"], "result"=>"null");
	echo json_encode($result);
}else{	
	// create variable for position
	$positions = array();
	while($row = mysql_fetch_array($sql))
	{
		// add new position to array
		array_push($positions, array('id'=>$row['id'], 'detail'=>$row['detail']));
	}
	$result = array("status"=>$codes["200"], "result"=>$positions);
	echo json_encode($result);
}
mysql_close($mysql_link);
?>