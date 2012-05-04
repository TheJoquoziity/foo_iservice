<?php
require('../global/db.php');
require('../global/status_code.php');

/* -- database --
item
id      brand      model    barcode    pic     bought_from     status      setup_at        remark

item_status Ð bought or rent
id      detail
*/

/* -- mode --
item_reg => register new item
item_search => search exist items
*/

$id = $_POST[id];
$brand = $_POST[brand];
$model = $_POST[model];
$barcode = $_POST[barcode];
//$pic = $_POST[pic];
$pic = null;
$bought_from = $_POST[bought_from];
$status = $_POST[status];
$setup_at = $_POST[setup_at];
$remark = $_POST[remark];

$mac_addr = $_POST[mac_addr]; // only on mobile mode ++++
$os_ver = $_POST[os_ver]; // only on mobile mode ++++

// check mode; call via mobile or web
$mode = $_POST[mode];

$search_parameter_query_string = "";

if($mode == "item_search"){
	// create new item
	if($brand != "") {
		$search_parameter_query_string .= "brand like '%".$brand."%' AND ";
	}
	if($model != "") {
		$search_parameter_query_string .= "model like '%".$model."%' AND ";
	}
	if($barcode != "") {
		$search_parameter_query_string .= "barcode like '%".$barcode."%' AND ";
	}
	if($bought_from != "") {
		$search_parameter_query_string .= "bought_from like '%".$bought_from."%' AND ";
	}
	if($status != ""){
		$search_parameter_query_string .= "status like '%".$status."%' AND ";
	}
	if($setup_at != ""){
		$search_parameter_query_string .= "setup_at like '%".$setup_at."%' AND ";
	}
	// check parameters
	if($search_parameter_query_string == "") {
		$result = array("status"=>$codes["406"], "result"=>"Please fill at least 1 parameter to search");
		echo json_encode($result);
	}
	else {
		// remove " AND " in $search_parameter_query_string at the last position
		$search_parameter_query_string = "select * from item where " . $search_parameter_query_string;
		$search_parameter_query_string = substr($search_parameter_query_string, 0, -5);
		//echo $search_parameter_query_string;
		$result = mysql_query($search_parameter_query_string) or die(mysql_error());
		$num = mysql_num_rows($result);
		
		if($num <= 0){
			$result = array("status"=>$codes["404"], "result"=>"Not found any item");
		} else {
			$items = array();
			while ($row = mysql_fetch_array($result)) {
				$item = array(
					'id' => $row['id']
					, 'brand' => $row['brand']
					, 'model' => $row['model']
					, 'barcode' => $row['barcode']
					, 'pic' => $row['pic']
					, 'bought_from' => $row['bought_from']
					, 'status' => $row['status']
					, 'setup_at' => $row['setup_at']
					, 'remark' => $row['remark']
				);
				array_push($items, $item);
			}
			$result = array("status"=>$codes["200"], "result"=> $items);
		}
		
		echo json_encode($result);
	}
} else if($mode == "item_reg"){
	//echo "Item registration mode";
	
	// check already item barcode in database
	$result = mysql_query("select * from item where barcode ='".$barcode."'"); 
	$num = mysql_num_rows($result);
	//if there are already the item barcode,then exit
	if($num >0) {
		$result = array("status"=>$codes["406"], "result"=>"This barcode is already exists");
		echo json_encode($result);
		exit();
	}
	
	// check required parameters are not null
	if($brand == "" || $model == "" || $barcode == "" || $status == ""){
		
		$result = array("status"=>$codes["406"], "result"=>"Please fill in at least 'brand', 'model', 'barcode', and 'status' fields");
		echo json_encode($result);
		exit();
	}

	// if item is not duplicate
	// for 'pic' the value is 'null' in this version*
	$sql="INSERT INTO item (brand, model, barcode, pic, bought_from, status, setup_at, remark) VALUES
('$brand','$model','$barcode','$pic','$bought_from','$status','$setup_at','$remark')";

	// if can't insert value to database
	if (!mysql_query($sql,$mysql_link))
	{
		$result = array("status"=>$codes["503"], "result"=>"Can't register this item");
		echo json_encode($result);
		exit();
	}

	// finish create new item
	$result = array("status"=>$codes["201"], "result"=>"Successful");
	echo json_encode($result);

	//mysql_close($mysql_link);
} else if ($mode == "item_edit"){ ////////// Please check later /////////////
	//echo "Item edit mode";
	// check already item barcode in database
	$result = mysql_query("select * from item where barcode ='".$barcode."' and id != $id"); 
	$num = mysql_num_rows($result);
	//if there are already the item barcode,then exit
	if ($num >0) {
		$result = array("status"=>$codes["406"], "result"=>"This barcode is already exists");
		echo json_encode($result);
		exit();
	}
	
	// check required parameters are not null
	if($brand == "" || $model == "" || $barcode == "" || $status == "" || $bought_from == "" || $setup_at == ""){
		$result = array("status"=>$codes["406"], "result"=>"Please fill all fields");
		echo json_encode($result);
		exit();
	}
	$sql_update = mysql_query("UPDATE item SET brand = '$brand', model = '$model', barcode = '$barcode', bought_from = $bought_from, status = $status, setup_at = $setup_at, remark = '$remark' WHERE id = '$id'");
	// if can't insert value to database
	if (!$sql_update)
	{
		$result = array("status"=>$codes["503"], "result"=>"Can't register this item", "sql"=>$sql_update);
		echo json_encode($result);
		exit();
	} else {
		$result = array("status"=>$codes["200"], "result"=>"Successful");
		echo json_encode($result);
	}
}

mysql_close($mysql_link);

?>