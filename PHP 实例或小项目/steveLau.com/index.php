<?php

require __DIR__.'/lib/user.php';
require __DIR__.'/lib/article.php';

$pdo = require __DIR__.'/lib/db.php';

$user = new User($pdo);

print_r($user->register('Lau', 'steve'));

print_r($user->login('Lau', 'steve'));

$article = new Article($pdo);

print_r($article->create('article', 'content', 1));

//print_r($article->view(1));

//print_r($article->edit(1, 'article2', 'content_new', 1));

//var_dump($article->delete(2, 1));

//print_r($article->getlist(1, 1, 4));

?>
