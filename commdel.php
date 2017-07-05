<?php 

require('./lib/init.php');

$comment_id = $_GET['comment_id'];

//获取当前评论的art_id
$sql = "select art_id from comment where comment_id = $comment_id";
$art_id = mGetOne($sql);


//判断地址栏传来的留言ID是否合法
if (!is_numeric($comment_id)) {
	error('留言ID不合法');
}
//是否有这条留言
$sql = "select * from comment where comment_id = $comment_id";
if (!mGetRow($sql)) {
	error('留言不存在');
}

//删除留言
$sql = "delete from comment where comment_id=$comment_id";
if (!mQuery($sql)) {
	error('留言删除失败');
} else {
	//error('留言删除成功');
	header('Location:commlist.php');
}

if ($art_id) {
	$sql = "update art set comm = comm-1 where art_id = $art_id";
	mQuery($sql);
}

include(ROOT .'/view/admin/commlist.html');


 ?>