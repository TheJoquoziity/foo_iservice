<?php
$mysql_db = "foo_iservice";
$mysql_user = "root";
$mysql_pass = "1234";
$mysql_link = mysql_connect("localhost", $mysql_user, $mysql_pass);
mysql_select_db($mysql_db, $mysql_link);
mysql_query("SET NAMES UTF8");
?>