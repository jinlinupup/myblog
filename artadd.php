<?php

require('./lib/init.php');

$sql = "select * from cat";
$cats = mGetAll($sql);
//print_r($cats);
//exit();


if (empty($_POST)) {
	include(ROOT.'/view/admin/artadd.html');
} else {
	//检测标题是否为空
	$art['title'] = trim($_POST['title']);
	if ($art['title'] == '') {
		error('标题不能为空');
	}
	//检测栏目是否合法
	$art['cat_id'] = $_POST['cat_id'];
	if (!is_numeric($art['cat_id'])) {
		error('栏目不合法');
	}
	//检测内容是否为空
	$art['content'] = trim($_POST['content']);
	if ($art['content'] == '') {
		error('内容不能为空');
	}

	//判断是否有图片上传 且error 是否为0
	if (!($_FILES['pic']['name'] == '') && $_FILES['pic']['error'] == 0) {
		$filename = createDir().'/'.randStr().getExt($_FILES['pic']['name']);
		if(move_uploaded_file($_FILES['pic']['tmp_name'], ROOT . $filename)) {
			$art['pic'] = $filename;
			$art['thumb'] = makeThumb($filename);
		}
	}


	//插入发布时间
	//$current = date("Y-m-d H:i:s",time());
	$art['pubtime'] = time();
	
	//收集tag
	$art['arttag'] = trim($_POST['tag']);
	
	//插入内容到art表
	if (!mExec('art',$art)) {
		error('文章发布失败');
	} else {
		//判断是否有tag
		$art['tag'] = trim($_POST['tag']);
		if ($art['tag'] == '') {
			//将cat 表的num 字段 当前栏目下的文章数+1
			$sql = "update cat set num=num+1 where cat_id=$art[cat_id]";
			mQuery($sql);
			succ('文章发布成功');
		} else {
			//获取上一次insert操作产生的主键ID
			$art_id = getLastId();
			//插入tag 到tag 表
			$tag = explode(',', $art['tag']);//索引数组
			$sql = "insert into tag (art_id,tag) values ";
			foreach ($tag as $v) {
				$sql .= "(".$art_id.",'".$v."'),";
			}
			$sql = rtrim($sql,',');
			//echo $sql;
			if(mQuery($sql)) {
				//将cat 表的num 字段 当前栏目下的文章数+1
				$sql = "update cat set num = num + 1 where cat_id = $art[cat_id]";
				mQuery($sql);
				succ('文章发布成功');
			} else {
				//tag 添加失败 删除原文章
				$sql = "delete from art where art_id = $art_id";
				if(mQuery($sql)){
					//将cat 表的num 字段 当前栏目下的文章数-1
				$sql = "update cat set num = num - 1 where cat_id = $art[cat_id]";
					error('文章发布失败');
				}
			}
		}
		
	}
}



?>
