<?php

$result=file_get_contents('http://identi.ca/api/statuses/public_timeline.as');
echo '<pre>';
print_r(json_decode($result));


?>