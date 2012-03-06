<?php
require_once("../lib/Parser.php");

$rssFeed = "http://christinsujith.wordpress.com/comments/feed/";
$rssComments = "http://christinsujith.wordpress.com/comments/feed/";


$b = new Parser();
$feedResponse = $b->runExec($rssFeed);
$commentResponse =  $b->runExec($rssComments);


$feedResponse = array('rssfeed' => $b->xml2array($feedResponse));
$commentResponse = array('commentfeed' => $b->xml2array($commentResponse));

echo '<pre>';
print_r( array_merge( $feedResponse, $commentResponse ) );


?>