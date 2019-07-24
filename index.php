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
input[type=text],select {
    width: 600px;
    padding: 12px 15px;
    margin: 10px 5;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 200px;
    background-color: #4CAF50;
    color: white;
    padding: 14px 15px;
    margin: 10px 5;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

input[type=submit]:hover {
    background-color: #45a049;
}

div {
    width: 610px;
    border-radius: 5px;
    background-color: #A9A9A9;
    padding: 5px;
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
    width: 350px;
    border-radius: 5px;
    background-color: #DCDCDC;
    padding: 5px;
    display: inline-block;
    display: block;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    color: #0000FF;
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
<input id="url" type="text" name="url" placeholder="Enter Your URL...">
<input type="submit" value="Shorten" onclick="showUser(url.value); return false;">
</form>
</div>
</br>
<p id="report">
</p>
</body>
</html>
