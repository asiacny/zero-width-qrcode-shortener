<?php
  $dbconfig = "sqlite"; //可选值sqlite、mysql、oracle
  $sqlite_db = "./urls.sqlite"; //sqlite数据库路径
  $mysql_db = "mysql:host=db4free.net;dbname=youdbname;charset=utf8"; //mysql数据库自定义设置
  $oracle_db = "oci:dbname=yoursid;charset=UTF8"; //oracle数据库自定义设置
  $db_user = "dbuser"; //oracle或mysql用户名
  $db_pass = "dbpass"; //oracle或mysql密码
  $domain   = "https://ml.mk"; //本程序部署网址
  $countset = "on";				//开启日志记录，使用sqlite且访问量很大时可修改为非on字符
//  $id       = rand(3364, 195111);		//此处不生效，如有需要请在shorten.php重复判断中修改，默认使用58进制，短链接字符为3位数
?>