<?php
/**
 * 使用 PDO 连接数据库并返回数据库连接句柄
 */
$dbms='mysql'; 	     // 数据库类型
$host='localhost';   // 数据库主机名
$dbName='mydb';      // 使用的数据库
$user='root';        // 数据库连接的用户名
$pass='123456';      // 对应的密码
$dsn="$dbms:host=$host;dbname=$dbName";

try {
	$pdo = new PDO($dsn, $user, $pass);  // 初始化一个 PDO 对象
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 设置捕获异常
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // 设置禁止本地模拟 prepare
	echo "连接成功<br/>";
	return $pdo;
} catch (PDOException $e) {
	die ("Error!: " . $e->getMessage() . "<br/>");
}
?>
