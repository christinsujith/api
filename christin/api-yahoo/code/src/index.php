<?php

require_once(dirname(__FILE__).'/../common.inc.php');

$yql  = new YahooYQLQuery();

$data = $yql->execute('select * from delicious.feeds.popular;');

$news_feed = "select * from rss where url='http://rss.news.yahoo.com/rss/topstories' and title LIKE \"%China%\"";

$newsResponse = $yql->execute($news_feed);

$photo = $yql->execute("select * from upcoming.events where location='San Francisco' and search_text='dance'");

var_dump($photo); exit;

?>
