<?php


require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');

$result = $sd->digg->getAll();

var_dump($result);

?>
