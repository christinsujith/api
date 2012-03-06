<?php

require_once("../lib/reddit.php");

$username = 'christinsujith';
$password = 'christy';

$reddit = new reddit();
$reddit->login($username, $password);

$userData = array('userinfo' =>  $reddit->getUser());

// Fetch comments from reddit for the post by the comment id q79ac

$commentId = 'q79ac';
$pageInfo = array('comments' =>  $reddit->runExec("http://www.reddit.com/r/worldnews/comments/{$commentId}/.json"));

$result = array_merge($userData,$pageInfo);

echo '<pre>';
print_r($result);


?>