<?php

require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');

$result = $sd->topic->getInfo(array(
    'topic' => 'business'
));

foreach ($result->topics as $topic) {
    echo "Topic name: {$topic->name}\n";
}

?>
