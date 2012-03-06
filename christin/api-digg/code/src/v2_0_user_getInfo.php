<?php

require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');

$result = $sd->user->getInfo(array(
    'usernames' => 'christinsujith'
));

print_r($result); exit;
foreach ($result->users as $user) {
    echo "User: {$user->name} is near {$user->location}.\n";
}

?>
