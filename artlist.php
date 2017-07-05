<?php 

require('./lib/init.php');


if (!acc()) {
	// error('请登录');
	header('Location:login.php');
}


//分页代码
$sql = "select count(*) from art";//获取总的文章数
$num = mGetOne($sql);//总的文章数
$curr = isset($_GET['page']) ? $_GET['page'] : 1;//当前页码数
$cnt = 20;//每页显示条数
$page = getPage($num,$curr,$cnt);
//echo $num;exit();
$sql = "select art_id,title,pubtime,comm,catname from art left join cat on art.cat_id=cat.cat_id".
' order by art_id desc limit '.($curr-1)*$cnt.','.$cnt;
$arts = mGetAll($sql);
//print_r($arts);exit();

include(ROOT.'/view/admin/artlist.html');

?>