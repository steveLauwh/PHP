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

	// 登录功能，验证用户名和密码
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
		
		// 查询用户名和密码
		$sql = 'SELECT * FROM `user` WHERE `username` = :username AND `password`= :password';
		$password = $this->_md5($password);
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		if (!$stmt->execute()) {
			throw new Exception("服务器内部错误", ErrorCode::SERVER_INTERNAL_ERROR);
		}
		$user = $stmt->fetch(PDO::FETCH_ASSOC); // 返回一个索引为结果集列名的数组
		if (empty($user)) // 如果从数据库中取出为空，说明用户名或密码错误
		{
			throw new Exception("用户名或密码错误", ErrorCode::USERNAME_OR_PASSWORD_INVALID);			
		}

		// 登录成功，将用户信息保存在 session 中，不保存用户的密码
		unset($user['password']);

		print_r('<br>');
		return $user;
	}

	// 注册功能，插入用户名、密码和创建时间
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

		// 设置属性：错误码，抛出异常
		$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// 插入 SQL 语句
		$sql = 'INSERT INTO `user` (`username`, `password`, `created_at`) VALUES (:username, :password, :created_at)';
		$created_at = time(); // 返回当前的 Unix 时间戳
		$password = $this->_md5($password); // 使用 md5 对密码加密
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':created_at', date('Y-m-d H:i:s', $created_at)); // date() 格式化创建时间，与 SQL 定义一致
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

	// 使用 md5 加密，计算字符串的 MD5 散列值
	private function _md5($string, $key='key')
	{
		return md5($string . $key);
	}

	// 检查用户名是否存在
	private function _isUsernameExists($username)
	{
		$sql = 'SELECT * FROM `user` WHERE `username`=:username'; // SQL 查询语句
		$stmt = $this->_db->prepare($sql); // PDO::prepare 准备要执行 SQL 语句
		$stmt->bindParam(':username', $username); // PDOStatement::bindParam 绑定一个参数(:name 形式的参数名)到指定的变量名
		$stmt->execute(); // PDOStatement::execute 执行预处理语句
		$result = $stmt->fetch(PDO::FETCH_ASSOC); // 返回一个索引为结果集列名的数组

		return !empty($result); // 存在返回 true
	}
}
?>
