<?php

require('./lib/init.php');
$art_id = $_GET['art_id'];
//$rs = mQuery($sql);
//判断地址栏传来的art_id是否合法
if (!is_numeric($art_id)) {
	error('文章ID不合法');
}

$sql = "select cat_id from art where art_id = $art_id";
$row = mGetOne($sql);

//是否有这篇文章
$sql = "select * from art where art_id=$art_id";
if (!mGetRow($sql)) {
	error('文章不存在');
}

//删除文章

$sql = "delete from art where art_id=$art_id";
if (!mQuery($sql)) {
	error('文章删除失败');
} else {
	
	//succ('文章删除成功');
	//echo isset($row);
	$sql = "update cat set num = num - 1  where cat_id = $row";
	mQuery($sql);

	header('Location:artlist.php');
	
	
}

?>