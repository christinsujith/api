<?php

require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');
$sd->setURI("http://services.digg.com");

$result = $sd->comment->getRecent();

var_dump($result);exit;

?>
