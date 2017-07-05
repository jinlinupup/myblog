<?php  

require('./lib/init.php');

if (empty($_POST)) {
	include(ROOT.'/view/front/login.html');
} else {
	$user['name'] = trim($_POST['name']);
	if (empty($user['name'])) {
		error('用户名不能为空');
	}

	$user['password'] = trim($_POST['password']);
	if (empty($user['password'])) {
		error('密码不能为空');
	}
	$sql = "select * from user where name='$user[name]' and password='$user[password]'";
	if (!mGetRow($sql)) {
		error('用户名或者密码错误');
	} else {
		// header('Location:artlist.php');
		//succ('登陆成功');
		setcookie('name',$user['name']);
		header("Location:artlist.php");
	}
}


?>