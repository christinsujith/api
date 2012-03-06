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

$result = $sd->comment->bury(array(
    'comment_id' => '20100729223726:4fef610331ee46a3b5cbd740bf71313e'
));

echo "Comment now has {$result->comment->diggs} diggs\n";

?>
