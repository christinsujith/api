<?php
require_once("OAuth.php");

$consumer_key 	= "KEY";
$consumer_secret 	= "SECRET";
$server_uri 	= "http://changeme-mywebsite-here.com";
$headers 		= $this->params['url'];

$sha1 		= new OAuthSignatureMethod_HMAC_SHA1();
$token 		= new OAuthToken(null, null);
$consumer 		= new OAuthConsumer($consumer_key, $consumer_secret);
$request 		= OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $server_uri, $headers);
$built 		= $sha1->build_signature($request, $consumer, $token);


	$oauth_token = "";
	$oauth_token_secret = "";
	$headers = null;

	$server_uri = "http://api.myspace.com";
        $resource_uri = '/v1/users/337265958';
        
        $sha1 = new OAuthSignatureMethod_HMAC_SHA1();
        $token = new OAuthToken($oauth_token, $oauth_token_secret);
        $oauth_consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        
        $req = OAuthRequest::from_consumer_and_token($oauth_consumer, $token, "GET", $server_uri . $resource_uri, $headers);

        $req->sign_request($sha1, $oauth_consumer, $token);
        $resource_signed_request = $req->to_url();

var_dump($resource_signed_request);



?>
