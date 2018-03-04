# PHP 实例或小项目

* [基于 PHP 的 RESTful API 实例](https://github.com/steveLauwh/PHP/tree/master/PHP%20%E5%AE%9E%E4%BE%8B%E6%88%96%E5%B0%8F%E9%A1%B9%E7%9B%AE/steveLau.com)



### LAMP(LNMP)环境搭建

> **Linux: CentOS 7**

> **Apache**

```
wget http://mirrors.tuna.tsinghua.edu.cn/apache//httpd/httpd-2.4.29.tar.gz

tar -xvzf httpd-2.4.29.tar.gz 

wget http://mirrors.hust.edu.cn/apache//apr/apr-1.6.3.tar.gz

wget http://mirrors.hust.edu.cn/apache//apr/apr-util-1.6.1.tar.gz

wget ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.41.tar.gz

tar -xvzf apr-1.6.3.tar.gz

tar -xvzf apr-util-1.6.1.tar.gz

tar -xvzf pcre-8.41.tar.gz

mv apr-1.6.3 apr

mv apr-util-1.6.1 apr-util

mv apr apr-util httpd-2.4.29/srclib/

yum install expat-devel

./configure --prefix=/usr/local/apache -with-pcre=/usr/local/pcre-8.41/bin/pcre-config -with-included-apr

make

make install

cd /usr/local/apache/bin

./apachectl -k start

firewall-cmd --zone=public --add-port=80/tcp --permanent

systemctl restart firewalld.service
```

> **Nginx**

Nginx 的 http 模块使用 pcre 来解析正则表达式，所以需要在 Linux 上安装 pcre 库。

nginx 配置: /usr/local/nginx/conf/nginx.conf 

```
[CentOS 7 安装 Nginx](https://www.linuxidc.com/Linux/2016-09/134907.htm)

wget http://nginx.org/download/nginx-1.12.2.tar.gz

tar -xvzf nginx-1.12.2

cd nginx-1.12.2

./configure --prefix=/usr/local/nginx --with-pcre=../pcre-8.41/

make

make install

cd /usr/local/nginx/sbin/ 

./nginx  # 启动 Nginx
```

> **MySQL**

```
编译安装太麻烦，并且慢。

推荐：[CentOS 7 安装 MySQL 5.7.13](https://www.linuxidc.com/Linux/2017-03/141300.htm)
```

> **PHP 7**

```
wget http://cn2.php.net/get/php-7.1.15.tar.gz/from/this/mirror

tar -xvzf mirror

cd php-7.1.15

./configure --prefix=/usr/local/php7 --enable-fpm   #配置

make  #编译

make install #安装
```
