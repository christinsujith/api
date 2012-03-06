<?php

require_once("../lib/friendfeed.php");

// this is the name we receive, when we register with friendfeed
$friendfeed_nickname = 'christinsujith';

// this is the remote api key from friendfeed
$friendfeed_key = 'hole712given';

// any nickname to get the feed
$nickname = 'bret';

// Pull a user feed:

$friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
$feed = $friendfeed->fetch_user_feed($nickname, $service=null, $start=0,$num=30);

print_r($feed);

/*
 * Pull a user comment feed:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->fetch_user_comments_feed($nickname, $service=null, $start=0,$num=30);
 * 
 */

/*
 * Pull a user likes feed:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->fetch_user_likes_feed($nickname, $service=null, $start=0,$num=30);
 * 
 */

/*
 * Pull a user discussion feed:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->fetch_user_discussion_feed($nickname, $service=null, $start=0,$num=30);
 * 
 */

/*
 * Pull a merged feed with all of the given users’ entries. Note that authentication is required if any one of the users’ feeds isn’t public and that $nicknames is an array of users:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->fetch_multi_user_feed($nicknames, $service=null, $start=0,$num=30);
 * 
 */


/*
 * Pull the entries the authenticated user sees on their home page:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->fetch_home_feed($service=null, $start=0, $num=30);
 * 
 */

/*
 * Perform a search. The format for $query is the same as the FriendFeed search. If you authenticate the request the search applies to the authenticated user. If it’s not authenticated the search is against all public feeds.
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->search($query, $service=null, $start=0, $num=30);
 * 
 */

/*
 * Publishes the provided link/title to the authenticated users page:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->publish_link($title, $link, $comment=null, $image_urls=null,$images=null, $via=null, $audio_urls=null, $audio=null, $room=null);
 * 
 */

/*
 * Add a comment to a users page; returns the comment_id
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->add_comment($entry_id, $body);
 * 
 */

/*
 * Edit a comment on a users page:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->edit_comment($entry_id, $comment_id, $body);
 * 
 */

/*
 * Delete a comment:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->delete_comment($entry_id, $comment_id);
 * 
 */

/*
 * Un-deletes a comment:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->undelete_comment($entry_id, $comment_id);
 * 
 */

/*
 * Add like for a given $entry_id:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->add_like($entry_id);
 * 
 */

/*
 * Deletes a like for a give $entry_id:
    $friendfeed = new FriendFeed($friendfeed_nickname, $friendfeed_key);
    $feed = $friendfeed->delete_like($entry_id);
 * 
 */
?>