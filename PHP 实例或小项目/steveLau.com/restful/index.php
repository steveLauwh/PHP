<?php

require __DIR__.'/../lib/user.php';
require __DIR__.'/../lib/article.php';

// 获取数据库连接句柄
$pdo = require __DIR__.'/../lib/db.php';

// Restful 类
class Restful
{
	private $_user;    // 用户对象

	private $_article;  // 文章对象

	private $_requestMethod; // 请求方法

	private $_resourceName;  // 资源名

	private $_id; // 文章ID

	private $_allowResources = ['users', 'articles']; // 资源用名词复数

	/**
	 *  GET 是从服务器获取数据
 	 *	POST 向服务器发送所需要处理的数据
	 *  HEAD 获取与GET方法相应的头部信息
	 *  PUT 更新或者替换一个现有的资源
	 *  DELETE 删除一个服务器上的资源
	 *  TRACE 对传到服务器上的头部信息进行追踪
     *  OPTION 获取该服务器支持的获取资源的 http 方法
	 */
	private $_allowRequestMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; // HTTP 动词

	// HTTP 状态码
	private $_statusCodes = [
		200 => 'Ok',
		204 => 'No Content',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		500 => 'Server Internal Error'
	];

	// 构造函数
	public function __construct(User $_user, Article $_article)
	{
		$this->_user = $_user;
		$this->_article = $_article;
	}

	// 执行函数
	public function run()
	{
		try {
			$this->_setupRequestMethod(); // 请求方法
			$this->_setupResource();  // 请求资源

			// 资源名为 users
			if ($this->_resourceName == 'users') {
				$this->_json($this->_handleUser());
			} else {
				// 资源名为 articles，以 json 格式
				$this->_json($this->_handleArticle());
			}
		} catch (Exception $e) {
			$this->_json(['error' => $e->getMessage()], $e->getCode());			
		}
	}

	// json 响应
	private function _json($array, $code = 0)
	{
		if ($array === null && $code === 0) {
			$code = 204;
		}
		if ($array !== null && $code === 0) {
			$code = 200;
		}

		if ($code > 0 && $code != 200 && $code != 204) {
			header("HTTP/1.1 " . $code . " " . $this->_statusCodes[$code]);
		}

		header('Content-Type:application/json;charaset=utf-8');
		echo json_encode($array, JSON_UNESCAPED_UNICODE); // 将数值转换成json数据存储格式
		exit();
	}

	// 获取客户端 HTTP 请求方法
	private function _setupRequestMethod()
	{
		$this->_requestMethod = $_SERVER['REQUEST_METHOD'];
		// 查看请求方法是否符合定义范围内
		if (!in_array($this->_requestMethod, $this->_allowRequestMethods))
		{
			throw new Exception("请求方法不被允许", 405);
		}
	}

	// 获取客户端发送资源 URI 
	private function _setupResource()
	{
		$path = $_SERVER['PATH_INFO'];
		$params = explode('/', $path);
		$this->_resourceName = $params[1];

		if (!in_array($this->_resourceName, $this->_allowResources)) {
			throw new Exception("请求资源不被允许", 400);
		}

		if (!empty($params[2])) {
			$this->_id = $params[2];
		}
	}

	// 处理 users 资源的请求
	private function _handleUser()
	{
		if ($this->_requestMethod != 'POST') {
			throw new Exception("请求方法不被允许", 405);
		}

		$body = $this->_getBodyParams();
		if (empty($body['username'])) {
			throw new Exception("用户名不能为空", 400);
		}
		
		if (empty($body['password'])) {
			throw new Exception("密码不能为空", 400);
		}

		return $this->_user->register($body['username'], $body['password']); // 注册
	}

	// 处理 articles 资源的请求
	private function _handleArticle()
	{
		switch ($this->_requestMethod) {
			case 'POST':
				return $this->_handleArticleCreate(); // 文章创建
			case 'PUT':
				return $this->_handleArticleEdit(); // 文章编辑
			case 'DELETE':
				return $this->_handleArticleDelete(); // 文章删除
			case 'GET':
				if (empty($this->_id)) {
					return $this->_handleArticleList(); // 文章列表
				} else {
					return $this->_handleArticleView(); // 文章查看
				}
			default:
				throw new Exception("请求方法不被允许", 405);
		}
	}

	// 获取请求体
	private function _getBodyParams()
	{
		$raw = file_get_contents('php://input');
		if (empty($raw)) {
			throw new Exception("请求参数错误", 400);
		}

		return json_decode($raw, true); // 解码
	}

	// 文章创建：先登录用户名和密码，然后创建文章
	private function _handleArticleCreate()
	{
		$body = $this->_getBodyParams();
		if (empty($body['title'])) {
			throw new Exception("文章标题不能为空", 400);
		}
		if (empty($body['content'])) {
			throw new Exception("文章内容不能为空", 400);
		}
		$user = $this->_userLogin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
		try {
			$article = $this->_article->create($body['title'], $body['content'], $user['user_id']);
			return $article;
		} catch (Exception $e) {
			if (!in_array($e->getCode(),
				[
					ErrorCode::ARTICLE_TITLE_CANNOT_EMPTY,
					ErrorCode::ARTICLE_CONTENT_CANNOT_EMPTY
				])) {
				throw new Exception($e->getMessage(), 400);
			}
			throw new Exception($e->getMessage(), 500);
		}
	}

	// 文章编辑：先登录用户名和密码，根据文章ID，获取文章记录
	private function _handleArticleEdit()
	{
		$user = $this->_userLogin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);	
		try {
			$article = $this->_article->view($this->_id);
			if ($article['user_id'] != $user['user_id']) {
				throw new Exception("您无权编辑", 403);
			}
			$body = $this->_getBodyParams();
			$title = empty($body['title']) ? $article['title'] : $body['title'];
			$content = empty($body['content']) ? $article['content'] : $body['content'];
			if ($title == $article['title'] && $content == $article['content']) {
				return $article;
			}
			return $this->_article->edit($article['article_id'], $title, $content, $user['user_id']);
		} catch (Exception $e) {
			if ($e->getCode() < 100) {
				if ($e->getCode() == ErrorCode::ARTICLE_NOT_FOUND) {
					throw new Exception($e->getMessage(), 404);
				} else {
					throw new Exception($e->getMessage(), 400);
				}
			} else {
				throw $e;
			}
		}
	}

	// 文章的删除
	private function _handleArticleDelete()
	{
		$user = $this->_userLogin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);	
		try {
			$article = $this->_article->view($this->_id);
			if ($article['user_id'] != $user['user_id']) {
				throw new Exception("您无权编辑", 403);
			}

			$this->_article->delete($article['article_id'], $user['user_id']);
			return null;
		} catch (Exception $e) {
			if ($e->getCode() < 100) {
				if ($e->getCode() == ErrorCode::ARTICLE_NOT_FOUND) {
					throw new Exception($e->getMessage(), 404);
				} else {
					throw new Exception($e->getMessage(), 400);
				}
			} else {
				throw $e;
			}
		}	
	}

	// 文章列表
	private function _handleArticleList()
	{
		$user = $this->_userLogin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$size = isset($_GET['size']) ? $_GET['size'] : 10;
		if ($size > 100) {
			throw new Exception("分页大小最大为 100", 400);
		}
		return $this->_article->getList($user['user_id'], $page, $size); // 
	}

	// 查看文章
	private function _handleArticleView()
	{
		try {
			return $this->_article->view($this->_id);
		} catch (Exception $e) {
			if ($e->getCode() == ErrorCode::ARTICLE_NOT_FOUND) {
				throw new Exception($e->getMessage(), 404);
			} else {
				throw new Exception($e->getMessage(), 500);
			}
		}
	}

	// 用户登录
	private function _userLogin($PHP_AUTH_USER, $PHP_AUTH_PW)
	{
		try {
			return $this->_user->login($PHP_AUTH_USER, $PHP_AUTH_PW);
		} catch (Exception $e) {
			if (in_array($e->getCode(),
				[
					ErrorCode::USERNAME_CANNOT_EMPTY,
					ErrorCode::PASSWORD_CANNOT_EMPTY,
					ErrorCode::USERNAME_OR_PASSWORD_INVALID
				])) {
				throw new Exception($e->getMessage(), 401);
			}
			throw new Exception($e->getMessage(), 500);
		}
	}
}

$article = new Article($pdo);
$user = new User($pdo);

$restful = new Restful($user, $article);
$restful->run(); // 执行

?>
