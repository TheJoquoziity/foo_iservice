<?php
require('../global/db.php');
require('../global/status_code.php');

$mode = $_POST['mode'];
// return value in database
$sql = mysql_query("select dealer.id did,dealer.name dna,dealer_branch.id dbid,dealer_branch.name dbna,dealer_status.id dsid,dealer_status.detail dsde from dealer,dealer_branch,dealer_status where (dealer_branch.status = '".$mode."' OR dealer_branch.status = 3) AND dealer_status.id = dealer_branch.status AND dealer_branch.dealer_id = dealer.id order by dna,dbna") or die(mysql_error());
$num = mysql_num_rows($sql);
if($num <=0) {
	$result = array("status"=>$codes["204"], "result"=>"No dealer in database");
	echo json_encode($result);
}else{
	// create variable for dealer branches
	$dealer_info = array();
	while($row = mysql_fetch_array($sql)) {
		// add a new name of dealer to array
		array_push($dealer_info, array(
				'name_id'=>$row['did']
				, 'name'=>$row['dna']
				, 'branch_id'=>$row['dbid']
				, 'branch_name'=>$row['dbna']
				, 'status_id'=>$row['dsid']
				, 'status'=>$row['dsde']
			));
	}
	$result = array("status"=>$codes["200"], "result"=>$dealer_info, "num"=>$num);
	echo json_encode($result);
}
mysql_close($mysql_link);
?>