<?php

require_once 'HTTP/OAuth/Consumer.php';
require_once 'Services/Digg2.php';

// set access and secret keys
$apiKey = 'a900eef379ae457d627beaf6d5980fa3';
$secretKey = '034e4321e83775c222e3028d9b3ec8a5';

// start session
session_start();


try {
  if (!isset($_GET['oauth_verifier'])) {

    // get request token here
    $consumer = new HTTP_OAuth_Consumer($apiKey, $secretKey);
    $consumer->getRequestToken('http://services.digg.com/oauth/request_token', 'http://localhost/api-digg/code/src/v2_0_comment_digg.php');
    $_SESSION['token']        = $consumer->getToken();
    $_SESSION['token_secret'] = $consumer->getTokenSecret();
    $url = $consumer->getAuthorizeUrl('http://digg.com/oauth/authorize');
    header('Location: '. $url);

  } else {

    // get access token here
    $consumer = new HTTP_OAuth_Consumer($apiKey, $secretKey, $_SESSION['token'], $_SESSION['token_secret']);
    $consumer->getAccessToken('http://services.digg.com/oauth/access_token', $_GET['oauth_verifier']);
    $_SESSION['token']        = $consumer->getToken();
    $_SESSION['token_secret'] = $consumer->getTokenSecret();

    // now proceed with authenticated API request
    $consumer = new HTTP_OAuth_Consumer($apiKey, $secretKey, $_SESSION['token'], $_SESSION['token_secret']);

    // initialize service object
    $service = new Services_Digg2;
    $service->setVersion('2.0');

    // connect service object to OAuth consumer object
    $service->accept($consumer);

    $result = $service->comment->digg(array(
        'comment_id' => '20100729223726:4fef610331ee46a3b5cbd740bf71313e'
    ));

    print_r($result);
  }
} catch (Exception $e) {
  echo 'ERROR: ' . $e->getMessage();
  var_dump($consumer->getLastResponse());

  exit();
}

?>
