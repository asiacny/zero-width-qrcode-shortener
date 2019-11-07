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
$shortened = $_GET["q"];
try {
    $db = new PDO("sqlite:$sqlitedb") or die("fail to connect db");
}
catch(Exception $e) {
    die($e);
}
if(strpos($shortened,'​') !==false || strpos($shortened,'‌') !==false){
	$zeroid = str_replace('/','',$shortened);
	$zeroid = str_replace('​','0',$zeroid);
	$zeroid = str_replace('‌','1',$zeroid);
	$zeroid = from_zerowidth_to10($zeroid);
	$result = $db->query("SELECT * from main where id='$zeroid' LIMIT 0, 1");
	while ($row = $result->fetchObject()) {
		$url = $row->url;
		$shortened = $row->shortened;
	}
}else{
	$shortened = str_replace('/','',$shortened);
	$result = $db->query("SELECT * from main where shortened='$shortened' LIMIT 0, 1");
	while ($row = $result->fetchObject()) {
		$url = $row->url;
	}
}
if (!empty($url)) {
	if ($countset === '1') {
		$lasttime = date("Y-m-d H:i:s");
		$db->query("UPDATE main set count=count+1,last_time='$lasttime' where shortened='$shortened'");
	}
    header("HTTP/1.1 301 Moved Permanently");
    header("location:" . $url);
} else {
    header("HTTP/1.1 301 Moved Permanently");
    header("location:" . $domain);
}
?>
