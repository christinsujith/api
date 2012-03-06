<?php
require_once("../lib/Parser.php");

$rss = "http://www.metacafe.com/api/videos/?vq=funny+dogs";


$b = new Parser();
$rss = $b->runExec($rss);

echo '<pre>';
print_r(json_decode(json_encode($b->xml2array($rss))));


?>
