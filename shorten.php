<?php
require_once "phpqrcode.php";
require_once 'config.php';
//10进制转62进制
function from10_to62($num) {
    $to = 62;
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to) ] . $ret;
        $num = bcdiv($num, $to);
    } while ($num > 0);
    return $ret;
}
//10进制转58进制(不包含 0OlI 字符)
function from10_to58($num) {
    $to = 58;
    $dict = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to) ] . $ret;
        $num = bcdiv($num, $to);
    } while ($num > 0);
    return $ret;
}
//10进制转56进制(不包含 01OolI 字符)
function from10_to56($num) {
    $to = 56;
    $dict = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to) ] . $ret;
        $num = bcdiv($num, $to);
    } while ($num > 0);
    return $ret;
}
//10进制转零宽度空白不显字符
function from10_to_zerowidth($num) {
    $to = 2;
    $dict = array ('&#x200b;','&#x200c;');
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to) ] . $ret;
        $num = bcdiv($num, $to);
    } while ($num > 0);
    return $ret;
}
//文本生成二维码base64字符串
function qrbase64($text) {
    ob_start();
    QRcode::png($text, false, L, 10, 1, false);
    $image = ob_get_clean();
    header('Content-Type: text/html');
	$ret = base64_encode($image);
    return $ret;
}
$url = $_POST['url'];
if (!preg_match("/(http|https|itms-services):\/\/(.*?)$/i", $url)) {
    echo '<h3 style="color:#FF0000;">Invalid url should start with http:// or https:// </h3>';
} elseif ($url == 'http://' || $url == 'https://' || $url == 'itms-services://') {
    echo '<h3 style="color:#FF0000;">Invalid url</h3>';
} else {
    try {
        $db = new PDO("sqlite:$sqlitedb") or die("fail to connect db");
		//重复记录判断
		$i=0;
		do {
			$i++;
			$id = rand(3364, 195112);	//如有需要，此处应和config.php中相同
			$sql = "SELECT COUNT(*) as count FROM main where id=$id;";
			$id_count= $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		} while ( $id_count[0]['count']>0 && $i<10 );
    }
    catch(Exception $e) {
        die($e);
    }
    try {
        $stmt = $db->prepare("INSERT INTO main (id, url, shortened, count, ip, create_time, user_agent, referer) VALUES (:id, :url, :shorturl, :count, :ip, :createtime, :useragent, :referer);");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':shorturl', $shorturl);
        $stmt->bindParam(':count', $count);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':createtime', $createtime);
        $stmt->bindParam(':useragent', $useragent);
        $stmt->bindParam(':referer', $referer);
        $zeroid = from10_to_zerowidth($id);
        $shorturl = from10_to58($id);
        $count = '0';
        $createtime = date("Y-m-d H:i:s");
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $referer = $_SERVER['HTTP_REFERER'];
        $stmt->execute();
    }
    catch(Exception $e) {
        die($e);
    }
    echo '<h3 style="color:#bdbdbd;">Zero Width url:&nbsp;&nbsp<a id="zerourl" style="color:#000000;text-decoration:none;" href="' . "$domain/$zeroid" . '/" target="_blank">' . "$domain/$zeroid" . '/</a></h3>';
    echo '<h3 style="color:#bdbdbd;">Short url:&nbsp;&nbsp<a id="shorturl" style="color:#000000;text-decoration:none;" href="' . "$domain/$shorturl" . '" target="_blank">' . "$domain/$shorturl" . '</a></h3>';
    echo '
	<h3><img src="data:image/png;base64,'.qrbase64($domain.'/'.$shorturl).'"></h3>';
    echo '
	<h3 style="color:#bdbdbd;">Your url is:&nbsp;&nbsp<a style="color:#000000;text-decoration:none;" href="' . $url . '" target="_blank">' . $url . '</a></h3>';
    echo '
	<h3><img src="data:image/png;base64,'.qrbase64($url).'"></h3>';
    $db = null;
}
?>
