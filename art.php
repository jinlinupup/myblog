<?php

require('./lib/init.php');

$art_id = $_GET['art_id'];

//查询所有栏目
$sql = "select cat_id,catname from cat";
$cats = mGetAll($sql);

//判断地址传来的art_id 是否合法
if (!is_numeric($art_id)) {
	header('Location:index.php');
}

//如果没有这篇文章 跳转到首页去
$sql = "select * from art where art_id=$art_id";
if (!mGetRow($sql)) {
	header('Location:index.php');
}

//查询文章
$sql = "select title,content,pubtime,catname,comm,thumb from art inner join cat on art.cat_id=cat.cat_id where art_id = $art_id";
$art = mGetRow($sql);
//print_r($art);exit();

//post 非空 代表有留言
if (!empty($_POST)) {
	$comm['nick'] = trim($_POST['nick']);
	$comm['email'] = trim($_POST['email']);
	$comm['content'] = trim($_POST['content']);
	$comm['pubtime'] = time();
	$comm['art_id'] = $art_id;
	$comm['ip'] = sprintf('%u',ip2long(getRealIp()));
	$rs = mExec('comment' , $comm);

	//评论发布成功 将art 表的comm + 1
	$sql = "update art set comm=comm+1 where art_id=$art_id";
	mQuery($sql);

	//跳转到上一个页面
	if ($rs) {
		$ref = $_SERVER['HTTP_REFERER'];
		header("Location:$ref");
	}
} 

//查询所有的留言
$sql = "select * from comment where art_id = $art_id";
$comms = mGetAll($sql);


//插入留言



include(ROOT .'/view/front/art.html');

?>