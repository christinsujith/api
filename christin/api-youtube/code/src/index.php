<?php
session_start();
require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
Zend_Loader::loadClass('Zend_Gdata_YouTube');

Zend_Loader::loadClass('Zend_Gdata_YouTube');

$yt = new Zend_Gdata_YouTube();

$videoEntry = $yt->getVideoEntry('sI_KJb7hkVY');

echo '<pre>';
//echo json_encode(printVideoEntry($videoEntry));
print_r(printVideoEntry($videoEntry));


function printVideoEntry($videoEntry) {
    // the videoEntry object contains many helper functions
    // that access the underlying mediaGroup object
    $arr = array();
    $rating = $videoEntry->getVideoRatingInfo();
    $arr['rating'] = $rating['average'];
    $arr['videoId'] = $videoEntry->getVideoId();
    $arr['videoTitle'] = $videoEntry->getVideoTitle();
    $arr['updated'] = $videoEntry->getUpdated();
    $arr['description'] = $videoEntry->getVideoDescription();
    $arr['category'] = $videoEntry->getVideoCategory();
    $arr['tags'] = implode(", ", $videoEntry->getVideoTags());
    $arr['watchPageUrl'] = $videoEntry->getVideoWatchPageUrl();
    $arr['flashPlayerUrl'] = $videoEntry->getFlashPlayerUrl();
    $arr['duration'] = $videoEntry->getVideoDuration();
    $arr['viewCount'] = $videoEntry->getVideoViewCount();
    $arr['geoLocation'] = $videoEntry->getVideoGeoLocation();
    $arr['recordedOn'] = $videoEntry->getVideoRecorded();
    
    /*
     see the paragraph above this function for more information on the 
     'mediaGroup' object. in the following code, we use the mediaGroup
     object directly to retrieve its 'Mobile RSTP link' child
     * 
     */
    $rtsp = array();
    foreach ($videoEntry->mediaGroup->content as $content) {
        if ($content->type === "video/3gpp") {
            $rtsp[] = $content->url;
        }
    }
    $arr['mobileRtspLink'] = $rtsp;

    $videoThumbnails = $videoEntry->getVideoThumbnails();
    
    $thumbNail = array();
    foreach ($videoThumbnails as $videoThumbnail) {
        $thumbNail['time'] = $videoThumbnail['time'];
        $thumbNail['url'] =$videoThumbnail['url'];
        $thumbNail['height'] = $videoThumbnail['height'];
        $thumbNail['width'] = $videoThumbnail['width'];
    }
    
    $arr['thumbNail'] = $thumbNail;
    
    
    $arr['comments'] = getAndPrintCommentFeed($videoEntry->videoId); //printing comments
    
    return $arr;
}


function getAndPrintCommentFeed($videoId) {
    $yt = new Zend_Gdata_YouTube();
    // set the version to 2 to retrieve a version 2 feed
    $yt->setMajorProtocolVersion(2);
    $commentFeed = $yt->getVideoCommentFeed($videoId);
    return printCommentFeed($commentFeed);
}

function printCommentFeed($commentFeed) {

    $comment = array();
    foreach ($commentFeed as $commentEntry) {

        $comment['comment'][] = printCommentEntry($commentEntry);

    }
    return $comment;
}

function printCommentEntry($commentEntry) {
        $comment = array();
        $comment['title'] = $commentEntry->title->text;
        $comment['content'] = $commentEntry->content->text;
        $comment['author'] = $commentEntry->author[0]->text;
        return $comment;
}


Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 


$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
$httpClient = 
  Zend_Gdata_ClientLogin::getHttpClient(
              $username = 'your user name',
              $password = 'your password',  
              $service = 'youtube',
              $client = null,
              $source = 'MySource', // a short string identifying your application
              $loginToken = null,
              $loginCaptcha = null,
              $authenticationURL);


$developerKey = 'AI39si5KZaxdZQSr4-eBBmw8FtIxXbrb6cAePgMDtr7YzgBmZixpu1vME_hhI8Tgqjx5P15TEPjyxZZvU_QzHq9SN9ssdEA94A';
$applicationId = 'Video uploader v1';
$clientId = '170150740704-mdip97ntj0tantks51d2e2b6fpcm4hrk.apps.googleusercontent.com';

$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);

// this example assumes that $yt is a fully authenticated YouTube service object
$yt->setMajorProtocolVersion(2);

$activityFeed = $yt->getActivityForUser("christin");
printActivityFeed($activityFeed);

/**
 * Print out an activity feed
 *
 * @param $activityFeed A Zend_Gdata_YouTube_ActivityFeed
 * @return void
 */
function printActivityFeed($activityFeed) {
  foreach($activityFeed as $activityEntry) {
    $author = $activityEntry->getAuthorName();
    $activityType = $activityEntry->getActivityType();
    switch ($activityType) {
      case 'video_rated':
        echo "$author rated video " . $activityEntry->getVideoId()->text . ' ' .
          $activityEntry->getRatingValue() . " stars\n";
        break;
      case 'video_shared':
        echo "$author shared video " . $activityEntry->getVideoId()->text . "\n";
        break;
      case 'video_favorited':
        echo "$author favorited video " . $activityEntry->getVideoId()->text . "\n";
        break;
      case 'video_commented':
        echo "$author commented on video " . $activityEntry->getVideoId()->text . "\n";
        break;
      case 'video_uploaded':
        echo "$author uploaded video " . $activityEntry->getVideoId()->text . "\n";
        break;
      case 'friend_added':
        echo "$author friended " . $activityEntry->getUsername()->text . "\n";
        break;
      case 'user_subscription_added':
        echo "$author subscribed to channel of " . 
          $activityEntry->getUsername()->text . "\n";
        break;
    }
  }
  
}

?>