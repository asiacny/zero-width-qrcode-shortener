<html>
<head>
<meta charset="utf-8"/>
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache,no-store, must-revalidate">
<meta http-equiv="Cache" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes"/>
<title>Url Shortener</title>
<link rel="icon" href="data:image/ico;base64,aWNv">
<script>
function showUser(str) {
    if (str == "") {
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
        str = encodeURIComponent(str);
        xmlhttp.open("POST", "shorten.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        xmlhttp.send("url=" + str);
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
    width: 150px;
    border-color: #000000;
    background-color: #000000;
    padding: 14px 15px;
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
<h2>Url Shortener</h2>
<div>
<form>
<input id="url" type="text" name="url" required="" autofocus="" placeholder="Enter Your URL (http:// or https://)...">
<input type="submit" value="Shorten" onclick="showUser(url.value); return false;">
</form>
</div>
</br>
<p id="report">
</p>
</body>
</html>
