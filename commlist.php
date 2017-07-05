<?php

require('./lib/init.php');

//查询所有留言
$sql = "select * from comment";
$comms = mGetAll($sql);

include(ROOT .'/view/admin/commlist.html');

?>