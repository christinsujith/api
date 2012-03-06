<?php
require_once("../lib/Parser.php");

$rss = "http://identi.ca/christinsujith/foaf";


$b = new Parser();
$rss = $b->runExec($rss);

echo '<pre>';
print_r(($b->xml2array($rss)));


?>