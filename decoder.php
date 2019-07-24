<?php
require_once 'config.php';
function from_zerowidth_to10($num) {
    $from = 2;
	$num = strval($num);
	$dict = '01';
	$len = strlen($num);
	$dec = 0;
	for($i = 0; $i < $len; $i++) {
		$pos = strpos($dict, $num[$i]);
		$dec = bcadd(bcmul(bcpow($from, $len - $i - 1), $pos), $dec);
	}
	return $dec;
}
$url = $_GET["q"];
try {
    $db = new PDO("sqlite:$sqlitedb") or die("fail to connect db");
}
catch(Exception $e) {
    die($e);
}
if(strpos($_GET['q'],'​') !==false || strpos($_GET['q'],'‌') !==false){
	$zeroid = str_replace('/','',$_GET['q']);
	$zeroid = str_replace('​','0',$zeroid);
	$zeroid = str_replace('‌','1',$zeroid);
	$zeroid = from_zerowidth_to10($zeroid);
	$result = $db->query("SELECT * from main where id='$zeroid' LIMIT 0, 1");
	while ($row = $result->fetchObject()) {
		$res = $row->url;
		$url = $row->shortened;
	}
}else{
	$result = $db->query("SELECT * from main where shortened='$url' LIMIT 0, 1");
	while ($row = $result->fetchObject()) {
		$res = $row->url;
	}
}
if (!empty($res)) {
	if ($countset === '1') {
		$lasttime = date("Y-m-d H:i:s");
		$db->query("UPDATE main set count=count+1,last_time='$lasttime' where shortened='$url'");
	}
    header("HTTP/1.1 301 Moved Permanently");
    header("location:" . $res);
} else {
    header("HTTP/1.1 301 Moved Permanently");
    header("location:" . $domain);
}
?>
