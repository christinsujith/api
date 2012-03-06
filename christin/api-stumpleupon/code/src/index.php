<?php

require_once("../lib/stumpleupon.php");

// api key registered in http://su.pr/settings/

$apiKey = '38ed96498d4b21a653175c2dc3b67a8e';
$userName = 'christinsujith';



$longUrl = urlencode('http://www.stumbleupon.com');

// crerating the post data for send via curl
$postData = sprintf("longUrl=%s&login=%s&apiKey=%s",
                            $longUrl,
                            $userName,
                            $apiKey);
     
// post url
$url = 'http://su.pr/api/shorten';

// creating the object for stumpleupon class
$stumpleUpon = new stumpleupon();


// call the function
$badge =  array('badge' => $stumpleUpon->runExec($url, $postData));


// this is written for getting the badge information
$badgeurl = urlencode('http://www.facebook.com/');
$burl = json_decode(file_get_contents("http://www.stumbleupon.com/services/1.01/badge.getinfo?url={$badgeurl}"));
$pageinfo = array('pageIngo' => $burl);  


// rss stumple upon


$rss = file_get_contents("http://rss.stumbleupon.com/user/Laura2908/");

$rssOut = $stumpleUpon->xml2array($rss);
    

echo '<pre>';
print_r(array_merge($badge, $pageinfo,$rssOut));


?>