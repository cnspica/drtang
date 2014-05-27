<?php
// 简单的进行血糖比较并发送飞信
include ("BloodCmp.php");
include ("fetion.php");

$cmp = new BloodCmp("***********", **);	// 填写你的电话号码和血糖值
$cmp->Blood_cmp();
?>
