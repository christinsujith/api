<?php

require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');

$result = $sd->user->getComments(array(
    'username' => 'christinsujith'
));

print_r($result); exit;
foreach ($result->comments as $comment) {
    echo "Comment: {$comment->comment_text}\n";
}

?>
