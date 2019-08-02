# 说明
#演示地址:

https://ml.mk

https://demo.ml.mk

1.解压本程序到域名根目录  

2.修改config.php，并保证数据库可写(chmod 666 urls.sqlite;chown www:www urls.sqlite)  

3.Nginx请添加以下配置  
location / {  
	rewrite ^/(.*)$ /decoder.php?q=$1;  
		try_files $uri $uri/ /decoder.php;  
}  
location ~* \.(sqlite|sqlite3|db)$ {  
	deny all;  
}  

4.Apache请修改.htaccess  
RewriteEngine On  
RewriteCond %{REQUEST_FILENAME} !-f  
RewriteCond %{REQUEST_FILENAME} !-d  
RewriteRule ^(.*)$ decoder.php?q=$1 [QSA,L]  

本程序使用SQLite文本数据库，访问量大请自行修改为MySQL或关闭访问次数记录  
phpliteadmin.php为第三方单文件sql管理程序，默认中文，密码admin  
