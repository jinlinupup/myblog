<meta charset="utf-8">
<?php

require('./lib/init.php');
//判断表单是否有post数据
if (empty($_POST)) {
	include(ROOT.'/view/admin/catadd.html');
} else {
	//如有POST，则判断catname是否为空
	$cat['catname'] = trim($_POST['catname']);
	if (empty($cat['catname'])) {

		error('栏目名称不能为空');
	}

	//连接数据库
	mConn();

	//检测栏目名是否已存在
	$sql = "select count(*) from cat where catname='$cat[catname]'";
	$rs = mQuery($sql);
	if(mysql_fetch_row($rs)[0] != 0){
		error('栏目已经存在');
		exit();
	}

	//将栏目写入栏目表
	//$sql = "insert into cat (catname) values ('$cat[catname]')";
	if (!mExec('cat',$cat)) {
		//echo '栏目插入失败';
		echo mysql_error();
	} else {
		//echo '栏目插入成功';
		succ('栏目插入成功');
	}
	//print_r($_POST);
}

?>