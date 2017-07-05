<meta charset="utf-8">
<?php
require('./lib/init.php');

//连接数据库

$sql = 'select * from cat';
$cats = mGetAll($sql);

include(ROOT .'/view/admin/catlist.html');

?>