<?php


require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');

$result = $sd->search->search(array(
    'query' => 'war',
    'domain' => 'cnn.com'
));

foreach ($result->stories as $story) {
    echo "War story on cnn.com: {$story->title} story_id: {$story->story_id}.\n";
}

?>
