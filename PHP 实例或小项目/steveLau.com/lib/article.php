<?php
require_once __DIR__.'/errorCode.php';

// Article 文章类
class Article
{
	private $_db;

	public function __construct($_db)
	{
		$this->_db = $_db;
	}

	// 创建文章，包括标题、内容和用户ID
	public function create($title, $content, $userId)
	{
		if (empty($title))
		{
			throw new Exception("Article title can not empty", ErrorCode::ARTICLE_TITLE_CANNOT_EMPTY);
		}

		if (empty($content))
		{
			throw new Exception("Article content can not empty", ErrorCode::ARTICLE_CONTENT_CANNOT_EMPTY);		
		}

		$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// 插入 SQL 语句
		$sql = 'INSERT INTO `article` (`title`, `content`, `user_id`) VALUES (:title, :content, :user_id)';
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':content', $content);
		$stmt->bindParam(':user_id', $userId);
		if (!$stmt->execute())
		{
			throw new Exception("Publish article error", ErrorCode::ARTICLE_CREATE_FAIL);
		}

		print_r('<br>');

		return [
			'article_id' => $this->_db->lastInsertId(),
			'title' => $title,
			'content' => $content,
			'user_id' => $userId
		];
	}

	// 根据文章ID查看文章记录
	public function view($articleId)
	{
		if (empty($articleId))
		{
			throw new Exception("Article Id can not empty", ErrorCode::ARTICLE_ID_CANNOT_EMPTY);		
		}

		$sql = 'SELECT * FROM `article` WHERE `article_id` = :articleId';
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':articleId', $articleId);
		$stmt->execute();

		$article = $stmt->fetch(PDO::FETCH_ASSOC);

		if (empty($article))
		{
			throw new Exception("ARTICLE NOT FOUND", ErrorCode::ARTICLE_NOT_FOUND);		
		}
		
		print_r('<br>');

		return $article;
	}

	// 编辑文章
	public function edit($articleId, $title, $content, $userId)
	{
		$article = $this->view($articleId);
		if ($article['user_id'] !== $userId)
		{
			throw new Exception("Do not edit the article", ErrorCode::PERMISSION_DENIED);
		}
		$title = empty($title) ? $article['title'] : $title;
		$content = empty($content) ? $article['content'] : $content;
		if ($title === $article['title'] && $content === $article['content'])
		{
			return $article;
		}
		
		// 更新 update 操作
		$sql = 'UPDATE `article` SET `title` = :title, `content` = :content WHERE `article_id` = :id';

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':content', $content);
		$stmt->bindParam(':id', $articleId);

		if (!$stmt->execute())
		{
			throw new Exception("The article edit fail", ErrorCode::ARTICLE_EDIT_FAIL);
		}

		return [
			'articleId' => $articleId,
			'title' => $title,
			'content' => $content,
			'userId' => $article['user_id']
		];
	}	

	// 删除文章
	public function delete($articleId, $userId)
	{
		$article = $this->view($articleId);
		if ($article['user_id'] !== $userId)
		{
			throw new Exception("Permission denied", ErrorCode::PERMISSION_DENIED);
		}

		// 删除 SQL 操作
		$sql = 'DELETE FROM `article` WHERE `article_id` = :articleId AND `user_id` = :userId';
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':articleId', $articleId);
		$stmt->bindParam(':userId', $userId);
		if (false === $stmt->execute())
		{
			throw new Exception("Delete fail", ErrorCode::ARTICLE_DELETE_FAIL);
		}

		return true;
	}

	// 根据页数显示文章
	public function getList($userId, $page = 1, $size = 10)
	{
		if ($size > 100)
		{
			throw new Exception("Page maximum is 100", ErrorCode::PAGE_SIZE_TO_BIG);
		}
		$sql = 'SELECT * FROM `article` WHERE `user_id` = :userId LIMIT :limit, :offset';
		$limit = ($page - 1) * $size;
		$limit = $limit < 0 ? 0 : $limit;
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':userId', $userId);
		$stmt->bindParam(':limit', $limit);
		$stmt->bindParam(':offset', $size);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}
}
?>
