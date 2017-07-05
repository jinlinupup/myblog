<?php  

/**
 * 成功的提示信息
 */
function succ($res) {
	$result = 'succ';
	require(ROOT.'/view/admin/info.html');
	exit();
}

/**
 * 失败返回的报错信息
 */

function error($res) {
	$result = 'fail';
	require(ROOT.'/view/admin/info.html');
	exit();
}

/**
 * 获取来访者的ip
 */

function getRealIp() {
	static $realip = null;
	if ($realip !== null) {
		return $realip;
	}
	if (getenv('REMOTE_ADDR')) {//获取一个环境变量的值，来访者的IP window
		$realip = getenv('REMOTE_ADDR');
	} else if (getenv('HTTP_CLIENT_IP')) {//liunx
		$realip = getenv('HTTP_CLIENT_IP');
	} else if (getenv('HTTP_X_FROWARD_FOR')) {//vpn
		$realip = getenv('HTTP_X_FROWARD_FOR');
	}

	return $realip;
}

/**
 * 生成分页代码
 * @param int $num 文章总数
 * @param int $curr 当前显示的页码数
 * @param int $cnt 每页显示的条数
 * 
 */

function getPage($num,$curr,$cnt) {
	//最大的页码数
	$max = ceil($num/$cnt);

	//最左侧页码
	$left = max(1,$curr-2);
	//最右侧页码
	$right = min($left+4,$max);

	$left = max(1,$right-4);

	$page = array();
	for($i=$left;$i<=$right;$i++) {
		// $page[$i] = 'page='.$i;
		$_GET['page'] = $i;
		$page[$i] = http_build_query($_GET);
	}
 
	return $page;
}	

/**
 * 生成随机字符串
 * @param int $num 生成的随机字符串个数
 * @param str 生成的随机字符串
 * 
 */
function randStr($num=6) {
	$str = str_shuffle('abcdefghjkmnpqrstuvwxyzaBCDEFGHJKMNPQRSTUVWXYZ23456789');//str_shuffle 打乱字符串
	return substr($str, 0,$num);//substr截取字符串
}
//echo randStr();

/**
 * 创建目录 
 */
function createDir() {
	$path = '/upload/'.date('Y/m/d');
	$fpath = ROOT . $path;
	if (is_dir($fpath) || mkdir($fpath,0777,true)) {
		return $path;
	} else {
		return false;
	}
}

// function createDir() {
// 	$path = '/upload/'.date('Y/m/d');
// 	$fpath = ROOT . $path;
// 	if(is_dir($fpath) || mkdir($fpath , 0777 , true)) {
// 		return $path;
// 	} else {
// 		return false;
// 	}
// }

/**
 * 获取文件后缀
 * @param  str 文件名
 * @return  str 文件的后缀名，且 带点 
 */

function getExt($filename) {
	return strchr($filename,'.'); 
}



/**
 * 生成缩略图
 * @param str $oimg  图片路径
 * @param int $sw 生成缩略图的宽
 * @param int $sh 生成缩略图的高
 * @return str 生成缩略图的路径
 */
function makeThumb($oimg,$sw=200,$sh=200) {
	//缩略图存放的路径
	$simg = dirname($oimg).'/'.randStr().'.png';

	//获取大图和缩略图的绝对路径
	$opath = ROOT . $oimg;
	$spath = ROOT . $simg;

	//创建缩略图画布
	$spic = imagecreatetruecolor($sw, $sh);
	//创建白色
	$white = imagecolorallocate($spic, 255, 255, 255);
	imagefill($spic, 0, 0, $white);

	//获取大图信息
	list($bw,$bh,$btype) = getimagesize($opath);

	/*
	1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，
	7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，
	11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM
	 */
	$map = array(
		1=>'imagecreatefromgif',
		2=>'imagecreatefromjpeg',
		15=>'imagecreatefromwbmp'
	);
	// if(!iseet($map[$btype])) {
	// 	return false;
	// }

	$opic = $map[$btype]($opath);//大图资源

	//计算缩略比
	$rate = min($sw/$bw,$sh/$bh);

	$zw = $bw * $rate;//最终返回的小图宽
	$zh = $bh * $rate;//最终返回的小图高

	imagecopyresampled($spic, $opic, ($sw-$zw)/2, ($sh-$zh)/2, 
		0, 0, $zw, $zh, $bw, $bh);

	imagepng($spic,$spath);

	imagedestroy($spic);
	imagedestroy($opic);

	return $simg;

}

/**
 * 用户登陆
 */

function acc() {
	return isset($_COOKIE['name']);
}

acc();

?>