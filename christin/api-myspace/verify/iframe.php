<div style="overflow:scroll">
    <div style="width: 500px; height: 500px">
<?php
echo "iframe";

require_once("OAuth.php");

//NOTE: get these from the "Edit Details" of http://developer.myspace.com
//NOTE: http://developer.myspace.com/Apps.mvc
$consumer_key     = "my-key";
$consumer_secret  = "my-secret";

//NOTE: this is the same url you would enter in as the External IFrame in "Edit Source"
$server_uri       = "http://mywebsite.com/verify/iframe.php";

//NOTE: this is the user of the canvas page
//http://profile.myspace.com/Modules/Applications/Pages/Canvas.aspx?appId=####&newinstall=1

//NOTE: all the additional querystrings from the iframe
$headers          = array(
    'appid' => $_GET['appid'],
    'country' => $_GET['country'],
    'installState' => $_GET['installState'],
    'lang' => $_GET['lang'],
    'oauth_consumer_key' => $_GET['oauth_consumer_key'],
    'oauth_nonce' => $_GET['oauth_nonce'],
    //'oauth_signature' => $_GET['oauth_signature'],
    'oauth_signature_method' => $_GET['oauth_signature_method'],
    'oauth_timestamp' => $_GET['oauth_timestamp'],
    'oauth_version' => $_GET['oauth_version'],
    'opensocial_owner_id' => $_GET['opensocial_owner_id'],
    'opensocial_token' => $_GET['opensocial_token'],
    'opensocial_viewer_id' => $_GET['opensocial_viewer_id'],
    'ownerId' => $_GET['ownerId'],
    'ptoString' => $_GET['ptoString'],
    'viewerId' => $_GET['viewerId'],
    );

//var_dump($headers);

$sha1             = new OAuthSignatureMethod_HMAC_SHA1();
$token            = new OAuthToken(null, null);
$consumer         = new OAuthConsumer($consumer_key, $consumer_secret);
$request          = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $server_uri, $headers);
$signature            = $sha1->build_signature($request, $consumer, $token);

echo "<pre>";

//NOTE: debug output
var_dump($signature);
var_dump($_GET);
var_dump($headers);

if ($_GET['oauth_signature'] == $signature) {
    echo "== same<br/>";
} else {
    echo "!= not the same<br/>";
}

echo "generated: ".$signature;
echo "<br/>";
echo "from iframe: ".$_GET['oauth_signature'];

echo "</pre>";
?>
<br/><br/>
    </div>
</div>