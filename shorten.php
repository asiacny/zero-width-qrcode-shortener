<?php
require_once "phpqrcode.php";
require_once 'config.php';
require_once 'ip.php';
//10进制转62进制(数字 + 小写字母 + 大写字母)，base_convert只能转2-36进制
//echo from10_to62(999999);
//echo from10_to58(999999);
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
//10进制转空白不显字符
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
    @QRcode::png($text, false, L, 10, 1, false);
    $image = ob_get_clean();
    header('Content-Type: text/html');
    $ret = base64_encode($image);
    return $ret;
}
$url = str_replace(' ','',$_POST['url']);
$qrset = @$_POST['qrset'];
error_reporting(E_ALL);
$ip = @$_SERVER['HTTP_CF_CONNECTING_IP'].",". @$_SERVER['HTTP_X_FORWARDED_FOR'].",".@$_SERVER['HTTP_X_REAL_IP'].",".@$_SERVER['HTTP_CLIENT_IP'].",".@$_SERVER['REMOTE_ADDR'];
if(!$ip) {
    $ip = "";
}
if (!preg_match("/^(http|https|itms-services):\/\/(.*?)$/i", $url)) {
    echo '<h3 style="color:#FF0000;">网址应以http://或https://开头 </h3>';
} elseif ($url == 'http://' || $url == 'https://' || $url == 'itms-services://') {
    echo '<h3 style="color:#FF0000;">请输入完整网址</h3>';
} elseif ($url == 'http://ml.mk' || $url == 'https://ml.mk' || $url == 'http://demo.ml.mk' || $url == 'https://demo.ml.mk') {
    echo '<h3 style="color:#FF0000;">请勿转换本站域名</h3>';
} elseif (preg_match("/^(http|https)(:\/\/)(ml\.mk|demo\.ml\.mk)\/(.*?)$/i", $url)) {
    echo '<h3 style="color:#FF0000;">请勿将本站短网址二次转换</h3>';
} elseif (preg_match("/^(http|https)(:\/\/)(lnk\.sk|114\.mx|mrw\.so|qr15\.cn|tr\.im|po\.st|xor\.tw|3ai\.ru|utka\.su|0\.gg|1t\.click|256\.so|3\.cn|4pn\.cn|985\.so|a\.aliyun\.com|a301\.cn|adf\.ly|bit\.do|bit\.ly|c\.tb\.cn|c7\.gg|cli\.gs|doiop\.com|dwarfurl\.com|dwz\.cn|dwz\.mn|dwz\.win|dx\.10086\.cn|fyad\.org|g\.10086\.cn|goo\.gl|hex\.io|idek\.net|is\.gd|j\.gs|j\.mp|jd\.cn\.hn|kl\.am|mcaf\.ee|miniurl\.com|moourl\.com|notlong\.com|ow\.ly|qq\.cn\.hn|quan\.mx|rebrand\.ly|rebrandly\.com|rrd\.me|rubyurl\.com|safe\.mn|shiturl\.com|shorl\.com|short\.ie|shrten\.com|smallurl\.in|snip\.ly|suo\.im|t\.cn|t\.im|tao\.bb|taourl\.com|tb\.am|tb\.cn\.hn|tinyurl\.com|u\.jd\.com|u\.nu|u\.pingan\.com|u6\.gg|url\.cn|url\.ms|urlm\.in|w\.url\.cn|w3t\.org|x\.co|x\.nu|y\.ahoo\.it|yep\.it|ymb\.bz|yourls\.org|z\.10085\.cn|zws\.im)\/(.*?)$/i", $url)) {
    echo '<h3 style="color:#FF0000;">本站不支持它站短网址的再次转换</h3>';
} elseif (preg_match("/^(http|https)(:\/\/)(yangkeduo\.com)\/(.*?)$/i", $url)) {
    echo '<h3 style="color:#FF0000;">黑名单域名禁止转换，详情联系urlblock@ml.mk</h3>';
} elseif (!preg_match("/^(http|https)(:\/\/)([a-zA-Z0-9][-a-zA-Z0-9]{0,62})(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})?(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})?(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})?\.([a-zA-Z0-9][-a-zA-Z0-9]{1,62})\/(.+?)$/i", $url)) {
    echo '<h3 style="color:#FF0000;">1.不支持中文域名<br>2.不支持五级(及以上)域名<br>3.非合法长网址（纯域名亦不支持）</h3>';
} else {
    try {
        if ($dbconfig == "oracle") {
            $db = new PDO($oracle_db,$db_user,$db_pass,array(PDO::ATTR_PERSISTENT => true,PDO::ATTR_TIMEOUT => 3)) or die("fail to connect db");
        } elseif ($dbconfig == "mysql") {
            $db = new PDO($mysql_db,$db_user,$db_pass,array(PDO::ATTR_PERSISTENT => true,PDO::ATTR_TIMEOUT => 3)) or die("fail to connect db");
        } else {
            $db = new PDO("sqlite:$sqlite_db") or die("fail to connect db");
        }
        //重复记录判断
        $i=0;
        do {
            $i++;
            $id = rand(3364, 195111);    //生成3字符短链接(支持19万条短链)
            if ($i>1) {
                $id = rand(195112, 11316495); //随机生成冲突时生成4字符(支持1112万条)
            }
            if ($i>2) {
                $id = rand(11316496, 656356767); //还是冲突的话生成5字符(支持6.45亿条)
            }
            if ($dbconfig == "oracle") {
                $sql = 'select count(*) as "count" from "main" where "id"='."'".$id."'";
            } else {
                $sql = 'select count(*) as count from main where id='."'".$id."'";
            }
            $id_count= $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } while ( $id_count[0]['count']>0 && $i<5 );
    }
    catch(Exception $e) {
        die($e);
    }
    $zeroid = from10_to_zerowidth($id);
    $shorturl = from10_to58($id); //默认使用58进制
    echo '<h3 style="color:#bdbdbd;">零宽链接:&nbsp;&nbsp<a id="zerourl" style="color:#000000;text-decoration:none;" href="' . "$domain/$zeroid" . '/" target="_blank" title="最后的斜杠/只是为了保证能完整复制两个斜杠之间看不见的零宽字符&#10;某些app(如微信)把零宽字符识别为空格只处理第一个斜杠前数据&#10;此时加第二个斜杠可方便对方成功复制并打开&#10;而在直接能打开链接的app里可以把最后一个斜杠删除，以便达到真正的零宽&#10;本站支持http和https双重跳转,可直接简单复制为ml.mk/发送(最后斜杠后面的不可见字符需保证复制成功)">' . "$domain/$zeroid" . '/</a></h3>';
    echo '<h3 style="color:#bdbdbd;">短链接:&nbsp;&nbsp<a id="shorturl" style="color:#000000;text-decoration:none;" href="' . "$domain/$shorturl" . '" target="_blank" title="本站支持http和https双重跳转,可直接简单复制为ml.mk/' . $shorturl . '打开">' . "$domain/$shorturl" . '</a></h3>';
    echo '
    <h3><img src="data:image/png;base64,'.qrbase64($domain.'/'.$shorturl).'"></h3>';
    echo '
    <h3 style="color:#bdbdbd;">你的长链接:<br><a style="color:#000000;text-decoration:none;word-wrap:break-word;word-break:break-all;" href="' . $url . '" target="_blank">' . $url . '</a></h3>';
    echo '
    <h3><img src="data:image/png;base64,'.qrbase64($url).'"></h3>';
    fastcgi_finish_request();
    $count = 0;
    $createtime = date("Y-m-d H:i:s");
    $useragent = @$_SERVER['HTTP_USER_AGENT'];
    $referer = @$_SERVER['HTTP_REFERER'];
    $ips = explode(',',$ip);	//字符串转数组
    $ips = array_flip($ips);	//利用键值唯一性，反转键名与键值去重，比array_unique效率高
    $ips = array_flip($ips);	//反转回来
    $ips = array_filter($ips);	//去重空键值,null,empty,""等等
    foreach($ips as $ipa) {
        $ipa = trim($ipa);
        $area .= convertip($ipa).",";
    }
	$area = rtrim($area,",");	//去除结尾符号
	$ip = implode(",",$ips);	//数组转字符串
    if ($dbconfig == "oracle") {
        $db->exec('INSERT INTO "main" ("id", "url", "shortened", "count", "ip", "area", "create_time", "user_agent", "referer") VALUES ('."'".$id."'".', '."'".$url."'".', '."'".$shorturl."'".', '."'".$count."'".', '."'".$ip."'".', '."'".$area."'".', '."'".$createtime."'".', '."'".$useragent."'".', '."'".$referer."'".')');
    } else {
        $db->exec('INSERT INTO main (id, url, shortened, count, ip, area, create_time, user_agent, referer) VALUES ('."'".$id."'".', '."'".$url."'".', '."'".$shorturl."'".', '."'".$count."'".', '."'".$ip."'".', '."'".$area."'".', '."'".$createtime."'".', '."'".$useragent."'".', '."'".$referer."'".')');
    }
    $db = null;
}
?>
