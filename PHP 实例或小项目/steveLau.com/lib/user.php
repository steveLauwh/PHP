<?php

require_once __DIR__.'/errorCode.php';

class User
{
	private $_db;

	public function __construct($_db)
	{
		$this->_db = $_db;
	}

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

	public function register($username, $password)
	{
		if (empty($username))
		{
			throw new Exception('Username is not empty', ErrorCode::USERNAME_CANNOT_EMPTY);
		}

		if (empty($password))
		{
			throw new Exception('Password is not empty', ErrorCode::PASSWORD_CANNOT_EMPTY);
		}

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

	private function _isUsernameExists($username)
	{
		$sql = 'SELECT * FROM `user` WHERE `username`=:username';
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		return !empty($result);
	}
}
?>