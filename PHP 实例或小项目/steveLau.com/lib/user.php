<?php

// 加载文件一次
// __DIR__ 指向当前执行的 PHP 脚本所在的目录
require_once __DIR__.'/errorCode.php';

// 定义 User 类，实现用户注册和登录
class User
{
	private $_db; // 私有变量

	// 构造函数
	public function __construct($_db)
	{
		// $this 代表自身的对象
		$this->_db = $_db;
	}

	// 登录功能
	public function login($username, $password)
	{
		if (empty($username))
		{
			throw new Exception('Username is not empty', ErrorCode::USERNAME_CANNOT_EMPTY);
		}

		if (empty($password))
		{
			throw new Exception('Password is not empty', ErrorCode::PASSWORD_CANNOT_EMPTY);
		}

		$sql = 'SELECT * FROM `user` WHERE `username` = :username AND `password`= :password';
		$password = $this->_md5($password);
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		if (!$stmt->execute()) {
			throw new Exception("服务器内部错误", ErrorCode::SERVER_INTERNAL_ERROR);
		}
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($user))
		{
			throw new Exception("用户名或密码错误", ErrorCode::USERNAME_OR_PASSWORD_INVALID);			
		}

		unset($user['password']);

		print_r('<br>');
		return $user;
	}

	// 注册功能
	public function register($username, $password)
	{
		// empty-检查用户名是否为空
		if (empty($username))
		{
			throw new Exception('Username is not empty', ErrorCode::USERNAME_CANNOT_EMPTY);
		}

		// empty-检查密码是否为空
		if (empty($password))
		{
			throw new Exception('Password is not empty', ErrorCode::PASSWORD_CANNOT_EMPTY);
		}

		// 检查用户名是否存在
		if ($this->_isUsernameExists($username)) 
		{
			throw new Exception('Username is Exist', ErrorCode::USERNAME_EXISTS);
		}

		$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = 'INSERT INTO `user` (`username`, `password`, `created_at`) VALUES (:username, :password, :created_at)';
		$created_at = time();
		$password = $this->_md5($password);
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':created_at', date('Y-m-d H:i:s', $created_at));
		if (!$stmt->execute())
		{ 
			throw new Exception('Error Register', ErrorCode::REGISTER_FAIL);
		}
		
		print_r('<br>');

		return [
			'user_id' => $this->_db->lastInsertId(),
			'username' => $username,
			'created_at' => date('Y-m-d H:i:s', $created_at)
		];
	}

	private function _md5($string, $key='key')
	{
		return md5($string . $key);
	}

	// 检查用户名是否存在
	private function _isUsernameExists($username)
	{
		$sql = 'SELECT * FROM `user` WHERE `username`=:username';
		$stmt = $this->_db->prepare($sql); // PDO::prepare 准备要执行 SQL 语句
		$stmt->bindParam(':username', $username); // PDOStatement::bindParam 绑定一个参数(:name 形式的参数名)到指定的变量名
		$stmt->execute(); // PDOStatement::execute 执行预处理语句
		$result = $stmt->fetch(PDO::FETCH_ASSOC); // 返回一个索引为结果集列名的数组

		return !empty($result); 
	}
}
?>
