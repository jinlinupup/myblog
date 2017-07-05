<meta charset="utf-8">
<?php
require('./lib/init.php');
$cat_id = $_GET['cat_id'];


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

if (empty($_POST)) {
	$sql = "select catname from cat where cat_id=$cat_id";
	$rs = mysql_query($sql);
	$cat = mysql_fetch_assoc($rs);

	include(ROOT.'/view/admin/catedit.html');
} else {
	$sql = "update cat set catname='$_POST[catname]' where cat_id= $cat_id";
	if (!mysql_query($sql)) {
		error('栏目修改失败');
	} else {
		succ('栏目修改成功');
	}
}

?>