<?php
require_once '../lib/apiClient.php';
require_once '../lib/contrib/apiPlusService.php';

session_start();

$client = new apiClient();

$client->setApplicationName('visalyze');
// Visit https://code.google.com/apis/console?api=plus to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('170150740704.apps.googleusercontent.com');
$client->setClientSecret('BNkNKVSF1Q2nU8hnGoxqu0CB');
$client->setRedirectUri('http://localhost/google/index.php');
$client->setDeveloperKey('AIzaSyAtnZFToVnte8Q1_7-mzWcbZhmG0_LBees');
$plus = new apiPlusService($client);

if (isset($_GET['code'])) {
  $client->authenticate(); 
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

// 114300667305397672177 is my id in google +

if ($client->getAccessToken()) {
  $me = $plus->people->get('114300667305397672177');

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $url = filter_var($me['url'], FILTER_VALIDATE_URL);
  $img = filter_var($me['image']['url'], FILTER_VALIDATE_URL);
  $name = filter_var($me['displayName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
  $personMarkup = "<a rel='me' href='$url'>$name</a><div><img src='$img?sz=82'></div>";
  
  $optParams = array('maxResults' => 100);
  $activities = $plus->activities->listActivities('114300667305397672177', 'public', $optParams);

  // var_dump($activities);
   print 'Your Activities: <pre>' . print_r($activities, true) . '</pre>';
  // echo strip_tags(json_encode($activities));

  // The access token may have been updated.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}