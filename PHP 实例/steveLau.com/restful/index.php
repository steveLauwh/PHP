<?php

require __DIR__.'/../lib/user.php';
require __DIR__.'/../lib/article.php';

$pdo = require __DIR__.'/../lib/db.php';

class Restful
{
	private $_user;

	private $_article;

	private $_requestMethod;

	private $_resourceName;

	private $_id;

	private $_allowResources = ['users', 'articles'];

	private $_allowRequestMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];

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

	public function __construct(User $_user, Article $_article)
	{
		$this->_user = $_user;
		$this->_article = $_article;
	}

	public function run()
	{
		try {
			$this->_setupRequestMethod();
			$this->_setupResource();

			if ($this->_resourceName == 'users') {
				return $this->_json($this->_handleUser());
			} else {
				return $this->_json($this->_handleArticle());
			}
		} catch (Exception $e) {
			$this->_json(['error' => $e->getMessage()], $e->getCode());			
		}
	}

	private function _json($array, $code = 0)
	{
		if ($code > 0 && $code != 200 && $code != 204) {
			header("HTTP/1.1 " . $code . " " . $this->_statusCodes[$code]);
		}

		header('Content-Type:application/json;charaset=utf-8');
		echo json_encode($array, JSON_UNESCAPED_UNICODE);
		exit();
	}

	private function _setupRequestMethod()
	{
		$this->_requestMethod = $_SERVER['REQUEST_METHOD'];
		if (!in_array($this->_requestMethod, $this->_allowRequestMethods))
		{
			throw new Exception("请求方法不被允许", 405);
		}
	}

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

		return $this->_user->register($body['username'], $body['password']);
	}

	private function _handleArticle()
	{
		switch ($this->_requestMethod) {
			case 'POST':
				return $this->_handleArticleCreate();
			case 'PUT':
				return $this->_handleArticleEdit();
			case 'DELETE':
				return $this->_handleArticleDelete();
			case 'GET':
				if (empty($this->_id)) {
					return $this->_handleArticleList();
				} else {
					return $this->_handleArticleView();
				}
			default:
				throw new Exception("请求方法不被允许", 405);
		}
	}

	private function _getBodyParams()
	{
		$raw = file_get_contents('php://input');
		if (empty($raw)) {
			throw new Exception("请求参数错误", 400);
		}

		return json_decode($raw, true);
	}

	private function _handleArticleCreate()
	{
		$body = $this->_getBodyParams();
		if (empty($body['title'])) {
			
		}
	}

	private function _handleArticleEdit()
	{

	}

	private function _handleArticleDelete()
	{

	}

	private function _handleArticleList()
	{

	}

	private function _handleArticleView()
	{

	}
}

$article = new Article($pdo);
$user = new User($pdo);

$restful = new Restful($user, $article);
$restful->run();

?>