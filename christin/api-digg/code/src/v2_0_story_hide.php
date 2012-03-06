<?php

require_once 'HTTP/OAuth/Consumer.php';
require_once 'Services/Digg2.php';

$hoc = new HTTP_OAuth_Consumer(
    'key',
    'secret',
    'access_token',
    'access_token_secret'
);

$sd = new Services_Digg2;
$sd->setVersion('2.0');
$sd->accept($hoc);

$result = $sd->story->hide(array(
    'story_id' => '20100719170232:22720024'
));

var_dump($result);exit;

?>
