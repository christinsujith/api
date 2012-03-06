<?php

$rss = file_get_contents("../lib/rss.xml");
require_once("../lib/Parser.php");

$b = new Parser();
var_dump($b->xml2array($rss));

?>