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
