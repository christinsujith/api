<?php
// Start a session, load the library
session_start();
require_once('../lib/tumblroauth/tumblroauth.php');
require_once('../lib/tumblroauth/tumblr.php');

// Define the needed keys
$consumer_key = "xYtGG2LMUNstekPRnfycjbkJgEFxSeu65fzjJWmx4TPCxttnl1";
$consumer_secret = "2VX9oIkzjC9d7KQRv88ekAp97o68oRyLQzGjvpAWubgDOEHSMc";

// Coded by christin 24-Feb-2012

// Once the user approves your app at Tumblr, they are sent back to this script.
// This script is passed two parameters in the URL, oauth_token (our Request Token)
// and oauth_verifier (Key that we need to get Access Token).
// We'll also need out Request Token Secret, which we stored in a session.

$tum_oauth = Tumblr::singleton($consumer_key,$consumer_secret);

// Ok, let's get an Access Token. We'll need to pass along our oauth_verifier which was given to us in the URL. 

// Coded by christin 24-Feb-2012
if(isset($_SESSION['tumblr_token']))
{

$access_token = $_SESSION['tumblr_token'];
}else{
 $access_token = $tum_oauth->getAccessToken($_REQUEST['oauth_verifier']);
$_SESSION['tumblr_token'] = $access_token;
}



// Now that we have an Access Token and Secret, we can make an API call.

// Any API call that requires OAuth authentiation will need the info we have now - (Consumer Key,
// Consumer Secret, Access Token, and Access Token secret).

// You should store the Access Token and Secret in a database, or if you must, a Cookie in the user's browser.
// Never expose your Consumer Secret.  It should stay on your server, avoid storing it in code viewable to the user.

// I'll make the /user/info API call to get some basic information about the user

// Start a new instance of TumblrOAuth, overwriting the old one.
// This time it will need our Access Token and Secret instead of our Request Token and Secret

$tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

// Make an API call with the TumblrOAuth instance.  There's also a post and delete method too.
$userinfo = $tum_oauth->get('http://api.tumblr.com/v2/user/info');

// You don't actually have to pass a full URL,  TukmblrOAuth will complete the URL for you.

// This will also work: $userinfo = $tum_oauth->get('user/info');

// Check for an error.
if (200 == $tum_oauth->http_code) {
  // good to go
} else {
  die('Unable to get info');
}


// Coded by christin 24-Feb-2012

$username = $userinfo->response->user->name;

$user = (array) $userinfo->response;

$parameter = array('api_key' => $consumer_key);
$bloginfo = $tum_oauth->get("http://api.tumblr.com/v2/blog/{$username}.tumblr.com/posts/photo", $parameter);


$blog = (array) $bloginfo->response;

echo '<pre>';
print_r(array_merge($user,$blog));

?>
