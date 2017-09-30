## PHP + MySQL

PHP 访问 MySQL 数据库的 API 和 MySQL 语句。

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
// PDO
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

CREATE TABLE 语句用于创建 MySQL 表。

```php
// 创建数据表(MySQLi - 面向对象)
$conn = new mysqli($servername, $username, $password, $dbname);

// 使用 sql 创建数据表
$sql = "CREATE TABLE MyGuests (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP
)";
```

```php
// 创建数据表(MySQLi - 面向过程)
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 使用 sql 创建数据表
$sql = "CREATE TABLE MyGuests (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP
)";
```

```php
// 创建数据表(PDO)
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
```

> **插入数据**

INSERT INTO 语句通常用于向 MySQL 表添加新的记录：

INSERT INTO table_name (column1, column2, column3,...) VALUES (value1, value2, value3,...)

PHP 中 SQL 查询语句必须使用引号。

在 SQL 查询语句中的字符串值必须加引号。

```php
$sql = "INSERT INTO MyGuests (firstname, lastname, email) VALUES ('John', 'Doe', 'john@example.com')";
```

> **插多条数据**

```php
// $conn->multi_query($sql)
// mysqli_multi_query($conn, $sql) 函数可用来执行多条SQL语句。
```

> **MySQL 预处理语句**

预处理语句对于防止 MySQL 注入是非常有用的。

预处理语句用于执行多个相同的 SQL 语句，并且执行效率更高。

* 预处理：创建 SQL 语句模板并发送到数据库。预留的值使用参数 "?" 标记 。例如：
INSERT INTO MyGuests (firstname, lastname, email) VALUES(?, ?, ?)

* 数据库解析，编译，对SQL语句模板执行查询优化，并存储结果不输出。

* 执行：最后，将应用绑定的值传递给参数（"?" 标记），数据库执行语句。应用可以多次执行语句，如果参数的值不一样。

> **从 MySQL 数据库读取数据**

`SELECT column_name(s) FROM table_name`

`SELECT * FROM table_name`

> **WHERE 子句**

WHERE 子句用于过滤记录。

```sql
SELECT column_name(s)
FROM table_name
WHERE column_name operator value
```

> **Order By 关键词**

ORDER BY 关键词用于对记录集中的数据进行排序。

ORDER BY 关键词默认对记录进行升序排序。

```sql
SELECT column_name(s)
FROM table_name
ORDER BY column_name(s) ASC|DESC
```

> **Update**

更新数据库中的数据

UPDATE 语句用于更新数据库表中已存在的记录。

```sql
UPDATE table_name
SET column1=value, column2=value2,...
WHERE some_column=some_value
```

> **Delete**

DELETE 语句用于从数据库表中删除行。

DELETE FROM 语句用于从数据库表中删除记录。

```sql
DELETE FROM table_name
WHERE some_column = some_value
```

