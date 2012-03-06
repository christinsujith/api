<?php
/* Last updated with phpFlickr 1.3.2
 *
 * This example file shows you how to call the 100 most recent public
 * photos.  It parses through them and prints out a link to each of them
 * along with the owner's name.
 *
 * Most of the processing time in this file comes from the 100 calls to
 * flickr.people.getInfo.  Enabling caching will help a whole lot with
 * this as there are many people who post multiple photos at once.
 *
 * Obviously, you'll want to replace the "<api key>" with one provided 
 * by Flickr: http://www.flickr.com/services/api/key.gne
 */

require_once("../lib/phpFlickr.php");

$f = new phpFlickr("9c08406ed87e1430d93ab415404d6d3b");

$recent = $f->photos_getRecent();

//var_dump( $recent ); exit;

/*
foreach ($recent['photos']['photo'] as $photo) {
    $owner = $f->people_getInfo($photo['owner']);
    echo "<a href='http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/'>";
    echo $photo['title'];
    echo "</a> Owner: ";
    echo "<a href='http://www.flickr.com/people/" . $photo['owner'] . "/'>";
    echo $owner['username'];
    echo "</a><br>";
}
 * 
 */


$photoId = '6794760528';
$comment = $f->photos_comments_getList($photoId);

echo '<pre>';
print_r(array_merge( $recent, $comment )); exit;

$userId = '77519730@N08';
$userProfile = $f->urls_getUserProfile($userId);

print_r($userProfile);
?>
