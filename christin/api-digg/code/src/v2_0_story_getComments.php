<?php


require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');

$result = $sd->story->getComments(array(
    'story_id' => '20100719170232:22720024'
));

foreach ($result->comments as $comment) {
    echo "Comment: '{$comment->text}'\n";
}

?>
