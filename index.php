<html>
<head>
<meta charset="utf-8"/>
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache,no-store, must-revalidate">
<meta http-equiv="Cache" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes"/>
<title>零宽短网址与二维码生成器 - Short URL & QR Code Generator</title>
<meta name="keywords" content="零宽,零宽短网址,隐藏网址,隐藏链接,零长度,零宽度,短网址生成,网址缩短,短网址,短链接,短域名,短地址,缩短网址,二维码,二维码短网址">
<meta name="description" content="带二维码的零长度隐藏短链接生成服务">
<link rel="icon" href="data:image/ico;base64,aWNv">
<script>
function showUser(url_str,qr_str) {
    if (url_str == "") {
        document.getElementById("url").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("report").innerHTML = this.responseText;
            }
        };
        if (qr_str == true) {
			str = "url=" + encodeURIComponent(url_str) + "&qrset=" + qr_str;
        } else {
			str = "url=" + encodeURIComponent(url_str);
		}
        xmlhttp.open("POST", "shorten.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        xmlhttp.send(str);
    }
}
</script>
<style>
input[type=text] {
    width: 500px;
    -webkit-transition: .7s;
    transition: .7s;
    background-color: #f7f7f7;
    padding: 12px 15px;
    margin: 10px 5;
    border: 1px solid #ccc;
    border-radius: 30px;
    box-sizing: border-box;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
@media (max-width: 767px) {
input[type=text] {
    width: 350px;
    -webkit-transition: .7s;
    transition: .7s;
    background-color: #f7f7f7;
    padding: 12px 15px;
    margin: 10px 5;
    border: 1px solid #ccc;
    border-radius: 30px;
    box-sizing: border-box;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
}
input[type=text]:focus {
    border-color: rgba(108,99,255,.5);
    outline: 0;
    background-color: #fff;
}
input[type=submit] {
    font-size: 15px;
    width: 130px;
    border-color: #000000;
    background-color: #000000;
    padding: 7px 5px;
    margin: 10px 5;
    color: white;
    border-radius: 30px;
    font-weight: 400;
    white-space: nowrap;
    cursor: pointer;
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 1.3em;
    transition: .7s;
    text-align: center;
    text-decoration: none;
}
input[type=submit]:hover {
    border-color: #000000;
    background-color: #000000;
}
input[type=submit]:focus {
    border-color: #000000;
    background-color: #000000;
    outline: 0;
}
div {
    padding: 1px;
    display: inline-block;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
#report {
    width: 400px;
    padding: 5px;
    display: inline-block;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
h2 {
    font-size: 2em;
    margin: .67em 0;
    display: inline-block;
    display: block;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}
h3 {
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}
</style>
</head>
<body>
<h4><a style="color:#000000;text-decoration:none;float:right;margin-right:10px" href="https://github.com/asiacny/zero-width-qrcode-sqlite-shortener" target="_blank">本站源码</a><a style="color:#000000;text-decoration:none;float:right;margin-right:10px" href="https://www.make.mk" target="_blank">热榜聚合</a></h4><br>
<h2>零宽短网址与二维码生成器</h2>
<div>
<form>
<input id="url" type="text" name="url" required="" autofocus="" title="使用说明在生成后的链接悬浮提示里，鼠标放在生成后的链接1秒以上(不用点击)即可查看" placeholder="输入长网址的时候记得带上 http:// 或 https://">
<center type="hidden" style="display:none"><input id="qrcode" type="checkbox" name="qrcode" style="vertical-align:middle;text-align:center;">仅二维码</center>
<input type="submit" value="缩短" title="使用说明在生成后的链接悬浮提示里，鼠标放在生成后的链接1秒以上(不用点击)即可查看" onclick="showUser(url.value,qrcode.checked); return false;">
</form>
</div>
</br>
<p id="report">
</p>
</body>
</html>