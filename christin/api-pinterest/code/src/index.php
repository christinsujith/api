<?php
require_once("../lib/Parser.php");

$rss = "http://pinterest.com/ninazulian/feed.rss";


$b = new Parser();
$rss = $b->runExec($rss);

echo '<pre>';
print_r(json_decode(json_encode($b->xml2array($rss))));


?>