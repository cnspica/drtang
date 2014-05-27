<?php
	$conn = mysql_connect("localhost:3306","root","******");	// 数据库用户、密码
	mysql_select_db("drtang", $conn);
	mysql_query("set names utf8");
?>
