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

$result = $sd->user->getSavedStories();

foreach ($result->stories as $story) {
    echo "Saved story: {$story->title} story_id: {$story->story_id}.\n";
}

?>
