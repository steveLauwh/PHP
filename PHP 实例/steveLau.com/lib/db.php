<?php
/**
 * 连接数据库并返回数据库连接句柄
 */
$dbms='mysql';
$host='localhost';
$dbName='mydb';
$user='root';
$pass='123456';
$dsn="$dbms:host=$host;dbname=$dbName";

try {
	$pdo = new PDO($dsn, $user, $pass);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	echo "连接成功<br/>";
	return $pdo;
} catch (PDOException $e) {
	die ("Error!: " . $e->getMessage() . "<br/>");
}
?>
