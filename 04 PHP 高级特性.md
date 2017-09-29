## PHP 多维数组

多维数组是包含一个或多个数组的数组。

```php
<?php 
$sites = array 
(  
    "google"=>array 
    ( 
        "Google", 
        "http://www.google.com" 
    ), 
    "Baidu"=>array 
    ( 
        "Baidu", 
        "http://www.baidu.com" 
    ) 
); 
print("<pre>");
print_r($sites); 
print("</pre>"); 
?>
```

## PHP date()

PHP date() 函数用于格式化时间/日期。

```php
<?php
echo date("Y/m/d") . "<br>";
echo date("Y.m.d") . "<br>";
echo date("Y-m-d");
?>
```

## PHP 包含文件

在 PHP 中，您可以在服务器执行 PHP 文件之前在该文件中插入一个文件的内容。

include 和 require 语句用于在执行流中插入写在其他文件中的有用的代码。

include 和 require 除了处理错误的方式不同之外，在其他方面都是相同的：

* require 生成一个致命错误（E_COMPILE_ERROR），在错误发生后脚本会停止执行。
* include 生成一个警告（E_WARNING），在错误发生后脚本会继续执行。

```php
include 'filename';

require 'filename';
```

## PHP 文件处理

`fopen()` 函数用于在 PHP 中打开文件。

`fclose()` 函数用于关闭打开的文件。

`feof()` 函数检测是否已到达文件末尾（EOF）。

`fgets()` 函数用于从文件中逐行读取文件。

`fgetc()` 函数用于从文件中逐字符地读取文件。

## PHP 文件上传

`$_FILES["file"]["name"]` - 上传文件的名称

`$_FILES["file"]["type"]` - 上传文件的类型

`$_FILES["file"]["size"]` - 上传文件的大小，以字节计

`$_FILES["file"]["tmp_name"]` - 存储在服务器的文件的临时副本的名称

`$_FILES["file"]["error"]` - 由文件上传导致的错误代码

## PHP Cookie

Cookie 常用于识别用户。Cookie 是一种服务器留在用户计算机上的小文件。每当同一台计算机通过浏览器请求页面时，这台计算机将会发送 Cookie。通过 PHP，您能够创建并取回 Cookie 的值。

> 创建 Cookie

`setcookie()` 函数设置 Cookie，必须位于 <html> 标签之前。
    
> 取回 Cookie 的值

PHP 的 `$_COOKIE` 变量用于取回 cookie 的值。

> 删除 Cookie

当删除 cookie 时，使过期日期变更为过去的时间点。

```
<?php
setcookie("user", "steve", time()+3600);
?>

<?php
echo $_COOKIE["user"];
?>

<?php
// 设置 cookie 过期时间为过去 1 小时
setcookie("user", "", time()-3600);
?>
```

## PHP Session

PHP session 变量用于存储关于用户会话（session）的信息，或者更改用户会话（session）的设置。Session 变量存储单一用户的信息，并且对于应用程序中的所有页面都是可用的。

Session 的工作机制是：为每个访客创建一个唯一的 id (UID)，并基于这个 UID 来存储变量。UID 存储在 cookie 中，或者通过 URL 进行传导。

> 启动会话

`session_start()` 函数必须位于 <html> 标签之前。

```php
<?php session_start(); ?>

<html>
<body>

</body>
</html>
```

> 存储 Session 变量

存储和取回 session 变量的正确方法是使用 `$_SESSION` 变量。

> 销毁 Session

删除某些 session 数据，可以使用 unset() 或 session_destroy() 函数。

`session_destroy()` 将重置 session，您将失去所有已存储的 session 数据。

## PHP Email

PHP mail() 函数用于从脚本中发送电子邮件。

`mail(to,subject,message,headers,parameters)`

PHP 运行邮件函数需要一个已安装且正在运行的邮件系统(如：sendmail、postfix、qmail等)。

防止 E-mail 注入：PHP 过滤器对输入进行验证。

使用 filter_var() 过滤 E-mail

* FILTER_SANITIZE_EMAIL 过滤器从字符串中删除电子邮件的非法字符
* FILTER_VALIDATE_EMAIL 过滤器验证电子邮件地址的值

如：`filter_var($field, FILTER_SANITIZE_EMAIL)`

## PHP 错误处理

错误处理方法：

* 使用 die() 函数：打印函数
* 自定义错误和错误触发器：`error_function(error_level, error_message, error_file, error_line, error_context)`
  
  设置错误处理程序：`set_error_handler` 函数
  
  触发错误：`trigger_error()` 函数
  
* 错误报告：在默认的情况下，根据在 php.ini 中的 error_log 配置，PHP 向服务器的记录系统或文件发送错误记录。通过使用 error_log() 函数，您可以向指定的文件或远程目的地发送错误记录。

## PHP 异常处理

当异常被抛出时，其后的代码不会继续执行，PHP 会尝试查找匹配的 "catch" 代码块。

Try、throw 和 catch

## PHP 过滤器

PHP 过滤器用于验证和过滤来自非安全来源的数据，比如用户的输入。

```
filter_var() - 通过一个指定的过滤器来过滤单一的变量

filter_var_array() - 通过相同的或不同的过滤器来过滤多个变量

filter_input - 获取一个输入变量，并对它进行过滤

filter_input_array - 获取多个输入变量，并通过相同的或不同的过滤器对它们进行过滤
```

## PHP JSON

使用 PHP 语言来编码和解码 JSON 对象。

对变量进行 JSON 编码：`string json_encode ( $value [, $options = 0 ] )`

对 JSON 格式的字符串进行解码，并转换为 PHP 变量：`mixed json_decode ($json [,$assoc = false [, $depth = 512 [, $options = 0 ]]])`

