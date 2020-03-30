<?php
require_once 'config.php';
require_once 'ip.php';
function from_zerowidth_to10($num) {
    $from = 2;
    $num = strval($num);
//    $dict = array ('&#x200b;','&#x200c;');
    $dict = '01';
    $len = strlen($num);
    $dec = 0;
    for($i = 0; $i < $len; $i++) {
        $pos = strpos($dict, $num[$i]);
        $dec = bcadd(bcmul(bcpow($from, $len - $i - 1), $pos), $dec);
    }
    return $dec;
}
$shortened = ltrim(rawurldecode($_SERVER['REQUEST_URI']),"/");
$shortenedsql = $shortened;
$ip = @$_SERVER['HTTP_CF_CONNECTING_IP'].",". @$_SERVER['HTTP_X_FORWARDED_FOR'].",".@$_SERVER['HTTP_X_REAL_IP'].",".@$_SERVER['HTTP_CLIENT_IP'].",".@$_SERVER['REMOTE_ADDR'];
if(!$ip) {
    $ip = "";
}
try {
    if ($dbconfig == "oracle") {
        $db = new PDO($oracle_db,$db_user,$db_pass,array(PDO::ATTR_PERSISTENT => true,PDO::ATTR_TIMEOUT => 5)) or die("fail to connect db");
    } elseif ($dbconfig == "mysql") {
        $db = new PDO($mysql_db,$db_user,$db_pass,array(PDO::ATTR_PERSISTENT => true,PDO::ATTR_TIMEOUT => 5)) or die("fail to connect db");
    } else {
        $db = new PDO("sqlite:$sqlite_db") or die("fail to connect db");
    }
}
catch(Exception $e) {
    die($e);
}
$lasttime = date("Y-m-d H:i:s");
$shortened = str_replace(array('/', '?', '？', '@', ')', '）', '(', '（', '+', '，', '.', '。', ',', '】', ']', '【', '[', '『', '』', '{', '}', '\\', '、', ' '),'',$shortened);

if(strpos($shortened,"​") !==false || strpos($shortened,"‌") !==false){
    $zeroid = str_replace('​','0',$shortened);
    $zeroid = str_replace('‌','1',$zeroid);
    $id = from_zerowidth_to10($zeroid);
    if ($dbconfig == "oracle") {
        $sql = 'select * from "main" where "id"='."'".$id."'";
    } elseif ($dbconfig == "mysql") {
        $sql = 'select * from main where binary id='."'".$id."'";
    } else {
        $sql = 'select * from main where id='."'".$id."'";
    }
    $result= $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    $url = $result[0]['url'];
    $id = "zeroid_".$result[0]['id'];
    $shortened = $result[0]['shortened'];
}else{
    #容错歧义(相似)字符(非58进制请自行修改或删除以下代码)
    if(strpos($shortened,"0") !==false || strpos($shortened,"O") !==false || strpos($shortened,"l") !==false || strpos($shortened,"I") !==false){
        $shortened = str_replace('0','o',$shortened);
        $shortened = str_replace('O','o',$shortened);
        $shortened = str_replace('l','1',$shortened);
        $shortened = str_replace('I','1',$shortened);
    }
    if ($dbconfig == "oracle") {
        $sql = 'select * from "main" where "shortened"='."'".$shortened."'";
    } elseif ($dbconfig == "mysql") {
        $sql = 'select * from main where binary shortened='."'".$shortened."'";
    } else {
        $sql = 'select * from main where shortened='."'".$shortened."'";
    }
    $result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    $url = $result[0]['url'];
    $id = $result[0]['shortened'];
}
if ($countset === "on") {
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
}
if (!empty($url)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Referrer-Policy: no-referrer");
    header("location:" . $url);
    fastcgi_finish_request();
    if ($countset === "on") {
        if ($dbconfig == "oracle") {
            $db->exec('INSERT INTO "log" ("url", "shortened", "ip", "area", "time", "user_agent", "referer") VALUES ('."'".$url."'".', '."'".$id."'".', '."'".$ip."'".', '."'".$area."'".', '."'".$lasttime."'".', '."'".@$_SERVER[HTTP_USER_AGENT]."'".', '."'".@$_SERVER[HTTP_REFERER]."'".')');
            $db->exec('UPDATE "main" set "count"="count"+1,"last_time"='."'".$lasttime."'".' where "shortened"='."'".$shortened."'");
        } elseif ($dbconfig == "mysql") {
            $db->exec('INSERT INTO log (url, shortened, ip, area, time, user_agent, referer) VALUES ('."'".$url."'".', '."'".$id."'".', '."'".$ip."'".', '."'".$area."'".', '."'".$lasttime."'".', '."'".@$_SERVER[HTTP_USER_AGENT]."'".', '."'".@$_SERVER[HTTP_REFERER]."'".')');
            $db->exec('UPDATE main set count=count+1,last_time='."'".$lasttime."'".' where binary shortened='."'".$shortened."'");
        } else {
            $db->exec('INSERT INTO log (url, shortened, ip, area, time, user_agent, referer) VALUES ('."'".$url."'".', '."'".$id."'".', '."'".$ip."'".', '."'".$area."'".', '."'".$lasttime."'".', '."'".@$_SERVER[HTTP_USER_AGENT]."'".', '."'".@$_SERVER[HTTP_REFERER]."'".')');
            $db->exec('UPDATE main set count=count+1,last_time='."'".$lasttime."'".' where shortened='."'".$shortened."'");
        }
    }
} else {
    header("HTTP/1.1 301 Moved Permanently");
    header("Referrer-Policy: no-referrer");
    header("location:" . $domain);
    fastcgi_finish_request();
    if ($countset === "on") {
        if ($dbconfig == "oracle") {
            $db->query('INSERT INTO "log" ("shortened", "ip", "area", "time", "user_agent", "referer") VALUES ('."'".$shortenedsql."'".', '."'".$ip."'".', '."'".$area."'".', '."'".$lasttime."'".', '."'".@$_SERVER[HTTP_USER_AGENT]."'".', '."'".@$_SERVER[HTTP_REFERER]."'".')');
        } else {
            $db->query('INSERT INTO log (shortened, ip, area, time, user_agent, referer) VALUES ('."'".$shortenedsql."'".', '."'".$ip."'".', '."'".$area."'".', '."'".$lasttime."'".', '."'".@$_SERVER[HTTP_USER_AGENT]."'".', '."'".@$_SERVER[HTTP_REFERER]."'".')');
        }
    }
}
$db = null;
?>
