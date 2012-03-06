<?php
// Includes
require_once("../lib/base/Common.php");
require_once("../lib/base/StandardQueryParameters.php");
require_once("../lib/MySpace.php");

require_once("Config.php"); // <-- SET YOUR KEY/SECRET HERE

// Set the key - application uri - secret key
$myspace = new MySpaceAPI(Config::$APP_OAUTH_CONSUMER_KEY, Config::$APP_OAUTH_CONSUMER_SECRET);


$user_id = 264730435; // <-- CHANGE to yours (you can get this from the iframe querystring for owner id and viewer id)


/*
// get the list of friends
// -----------------------------------------------------------------------------
echo "get_people_friends\n";
$result = $myspace->People->get_people_friends($user_id);
var_dump($result);
// -----------------------------------------------------------------------------


// get the list of friends with Parameters
// -----------------------------------------------------------------------------
// create the parameters
$query_params = new StandardQueryParameters();
$query_params->count = 1;
$query_params->start_index = 2;
var_dump($query_params);

// get the profile
echo "get_people_friends\n";
$result = $myspace->People->get_people_friends($user_id, $query_params);
var_dump($result);
// -----------------------------------------------------------------------------


// get the profile using a user_id
// -----------------------------------------------------------------------------
echo "get_people_profile\n";
$result = $myspace->People->get_people_profile($user_id);
var_dump($result);
// -----------------------------------------------------------------------------


// get the profile using a user_id with Parameters
// -----------------------------------------------------------------------------
// create the parameters
$query_params = new StandardQueryParameters();
$query_params->fields = "currentLocation,bodytype,name";
$query_params->count = 1;
var_dump($query_params);

// get the profile
echo "get_people_profile\n";
$result = $myspace->People->get_people_profile($user_id, $query_params);
var_dump($result);
// -----------------------------------------------------------------------------
*/

// -----------------------------------------------------------------------------

echo "----> running request_token, access_token example \n";

// Example Request using request/access

$request_token = $myspace->OAuthToken->get_request_token();
$req_token = $myspace->OAuthToken->get_token_from_url($request_token);
$req_token_secret = $myspace->OAuthToken->get_token_secret_from_url($request_token);


$callback = "http://igdev.myspace.com";
//$callback = "http://dev.sportsoverdose.com/callback";
$auth_url = $myspace->OAuthToken->get_authorization_url($callback, $req_token);

echo "\nPlease Authorize this request by going to the following url: \n" . $auth_url . "\n";

$counter = 20;
while ($counter > 0) {
  echo "\nContinuing in " . $counter . "s"; sleep(5); $counter -= 5;
}

$myspace->set_oauth_token($req_token);
$myspace->set_oauth_token_secret($req_token_secret);

$access_token = $myspace->OAuthToken->get_access_token();
$req_token = $myspace->OAuthToken->get_token_from_url($access_token);
$req_token_secret = $myspace->OAuthToken->get_token_secret_from_url($access_token);


$myspace->set_oauth_token($req_token);
$myspace->set_oauth_token_secret($req_token_secret);


// -----------------------------------------------------------------------------
echo "\n";

// get the current user profile and userId for other requests
// --------------------------------------------------------------------
echo "get_people_profile_current\n";
$result = $myspace->People->get_people_profile_current();
var_dump($result);
// --------------------------------------------------------------------


// get the list of current user profile with Parameters
// -----------------------------------------------------------------------------
// create the parameters
$query_params = new StandardQueryParameters();
//$query_params->count = 1;
//$query_params->start_index = 2;
$query_params->fields = "currentLocation,bodytype,name,photos,emails,gender,organizations,drinker";
var_dump($query_params);

// get the profile
echo "get_people_profile_current\n";
$result = $myspace->People->get_people_profile_current($query_params);
var_dump($result);
// -----------------------------------------------------------------------------


// get the current user profile and userId for other requests
// --------------------------------------------------------------------
echo "get_people_friends_current\n";
$result = $myspace->People->get_people_friends_current();
var_dump($result);
// --------------------------------------------------------------------


// get the list of current user friends with Parameters
// -----------------------------------------------------------------------------
// create the parameters
$query_params = new StandardQueryParameters();
//$query_params->count = 1;
//$query_params->start_index = 2;
$query_params->sort_by = "nickName";
var_dump($query_params);

// get the list friends
echo "get_people_friends_current\n";
$result = $myspace->People->get_people_friends_current($query_params);
var_dump($result);
// -----------------------------------------------------------------------------

echo "get_people_profile\n";
$result = $myspace->People->get_people_profile($user_id);
var_dump($result);

// -----------------------------------------------------------------------------

//NOT allowed
//$user_id = 63957326;
//echo "get_people_profile of friend\n";
//$result = $myspace->People->get_people_profile($user_id);
//var_dump($result);

// Activities //

echo "get_activities_current\n";
$result = $myspace->Actitivies->get_activities_current(null);
var_dump($result);

// -----------------------------------------------------------------------------
