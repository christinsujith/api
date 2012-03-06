<?php

$rss = file_get_contents("../lib/rss.xml");
require_once("../lib/Parser.php");
$b = new Parser();



$URL = "http://forum.developers.facebook.net/rss.php";

$ch = curl_init($URL);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_ENCODING, "gzip");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$rss = curl_exec($ch);
curl_close($ch);

echo "<pre>";
echo strip_tags(json_encode($b->xml2array($rss)));

//print_r(json_decode(strip_tags(json_encode($b->xml2array($rss)))));
?>