<?php
require('./lib/init.php');
$cat_id = $_GET['cat_id'];
echo $cat_id;


//检测栏目id是否为空
if (!is_numeric($cat_id)) {
	error('栏目不合法');
	exit();
}

//检测栏目是否存在
$sql = "select * from cat where cat_id=$cat_id";
$rs = mQuery($sql);
if(mysql_fetch_row($rs)[0] == 0){
	error('栏目不存在');
	exit();
}

//检测栏目下是否有文章
$sql = "select count(*) from art where cat_id=$cat_id";
$rs = mysql_query($sql);
if (mysql_fetch_row($rs)[0] != 0) {
	error('栏目下有文章，不能删除');
	exit();
}

//检测完毕，删除栏目
$sql = "delete from cat where cat_id=$cat_id";
if (!mysql_query($sql)) {
	error('栏目删除失败');
} else {
	succ('栏目删除成功');
}
?>