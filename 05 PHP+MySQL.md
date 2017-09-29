## PHP + MySQL

### PHP 连接 MySQL

MySQL 安装成功后，设置用户名和密码。

PHP 5 及以上版本建议使用以下方式连接 MySQL:

* MySQLi extension ("i" 意为 improved)
* PDO (PHP Data Objects)

其中 PDO 应用在 12 种不同的数据库中，MySQLi 只针对 MySQL 数据库。

两者都是面向对象。

### PHP 连接 MySQL 的 API

MySQLi 支持面向过程和面向对象。

> **连接 MySQL**

```php
// 创建连接(MySQLi - 面向对象)
$conn = new mysqli($servername, $username, $password);
```
```php
// 创建连接(MySQLi - 面向过程)
$conn = mysqli_connect($servername, $username, $password);
```
```php
$conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
```

> **关闭连接**

```php
// MySQLi - 面向对象
$conn->close();
```
```php
// MySQLi - 面向过程
mysqli_close($conn);
```
```php
// PDO
$conn = null;
```

> **创建数据库**

CREATE DATABASE 语句用于在 MySQL 中创建数据库。

```php
// 创建数据库(MySQLi - 面向对象)
$sql = "CREATE DATABASE myDB";
if ($conn->query($sql) === TRUE) {
    echo "数据库创建成功";
} else {
    echo "Error creating database: " . $conn->error;
}
```

```php
// 创建数据库(MySQLi - 面向过程)
$sql = "CREATE DATABASE myDB";
if (mysqli_query($conn, $sql)) {
    echo "数据库创建成功";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}
```

```php
// php使用 PDO 的最大好处是在数据库查询过程出现问题时可以使用异常类来 处理问题。

// 创建数据库(PDO)
$sql = "CREATE DATABASE myDBPDO"; 

// 使用 exec(), 因为没有结果返回 
$conn->exec($sql); 
echo "数据库创建成功<br>"; 
```

> **创建数据表**
