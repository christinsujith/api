<?php

require_once 'Services/Digg2.php';

$sd = new Services_Digg2;
$sd->setVersion('2.0');

$links = array('http://apple.com', 'http://google.com');

$result = $sd->story->getInfo(array(
    'links'     => implode(',', array_map('urlencode', $links)),
    'ctitles'   => 'best_on_camera_interview_since_bubb_rubb,Kings_Of_Leon_Walk_Offstage_After_Three_Songs',
    'story_ids' => '20100719170232:22720024'
));


foreach ($result->stories as $story) {
    echo "The story {$story->title} has {$story->diggs} diggs\n";
}

?>
