<?php
include ("BloodCmp.php");
include ("fetion.php");

$cmp = new BloodCmp("/* your telephone data */", /*Blood data*/);	// 填写你的电话号码和血糖值
$cmp->Blood_cmp();
?>
