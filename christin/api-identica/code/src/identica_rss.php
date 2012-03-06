<?php
require_once("../lib/Parser.php");

$rss = "http://identi.ca/rss";


$b = new Parser();
$rss = $b->runExec($rss);

echo '<pre>';
print_r(($b->xml2array($rss)));


?>