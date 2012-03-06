<?php
require_once('../lib/vimeo.php');
session_start();

// Create the object and enable caching
$vimeo = new phpVimeo('2bc1f59d282b8a2a5d0596c43cc84fb4', 'dad9151de41c4bd9');
$vimeo->enableCache(phpVimeo::CACHE_FILE, '../lib/cache', 300);


// Clear session
if (isset($_GET['clear']) == 'all') {
    session_destroy();
    session_start();
}

// Set up variables
if(isset($_SESSION['vimeo_state']))
{
$state = $_SESSION['vimeo_state'];
$request_token = $_SESSION['oauth_request_token'];
$access_token = $_SESSION['oauth_access_token'];
}

// Coming back
if (isset($_REQUEST['oauth_token']) != NULL && isset($_SESSION['vimeo_state']) === 'start') {
    $_SESSION['vimeo_state'] = $state = 'returned';
}

// If we have an access token, set it
if (isset($_SESSION['oauth_access_token']) != null) {
    $vimeo->setToken($_SESSION['oauth_access_token'], $_SESSION['oauth_access_token_secret']);
}

switch (isset($_SESSION['vimeo_state'])) {
    default:

        // Get a new request token
        $token = $vimeo->getRequestToken('http://localhost/api-vimeo/code/src/index.php');

        // Store it in the session
        $_SESSION['oauth_request_token'] = $token['oauth_token'];
        $_SESSION['oauth_request_token_secret'] = $token['oauth_token_secret'];
        $_SESSION['vimeo_state'] = 'start';

        // Build authorize link
        $authorize_link = $vimeo->getAuthorizeUrl($token['oauth_token'], 'write');

        break;

    case 'returned':

        // Store it
        if (isset($_SESSION['oauth_access_token']) === NULL && isset($_SESSION['oauth_access_token_secret']) === NULL) {
            // Exchange for an access token
            $vimeo->setToken($_SESSION['oauth_request_token'], $_SESSION['oauth_request_token_secret']);
            $token = $vimeo->getAccessToken($_REQUEST['oauth_verifier']);

            // Store
            $_SESSION['oauth_access_token'] = $token['oauth_token'];
            $_SESSION['oauth_access_token_secret'] = $token['oauth_token_secret'];
            $_SESSION['vimeo_state'] = 'done';

            // Set the token
            $vimeo->setToken($_SESSION['oauth_access_token'], $_SESSION['oauth_access_token_secret']);
        }

        // Do an authenticated call
        try {
            $videos = $vimeo->call('vimeo.videos.getUploaded');
            $activity = $vimeo->call('vimeo.activity.userDid');
            $peopleinfo = $vimeo->call('vimeo.people.getInfo');
            $videocomments = $vimeo->call('vimeo.groups.getVideoComments');
        }
        catch (VimeoAPIException $e) {
            echo "Encountered an API error -- code {$e->getCode()} - {$e->getMessage()}";
        }

        break;
}






?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vimeo Advanced API OAuth Example</title>
</head>
<body>

    <h1>Vimeo Advanced API OAuth Example</h1>
    <p>This is a basic example of Vimeo's new OAuth authentication method. Everything is saved in session vars, so <a href="?clear=all">click here if you want to start over</a>.</p>

    <?php if ($_SESSION['vimeo_state'] == 'start'): ?>
        <p>Click the link to go to Vimeo to authorize your account.</p>
        <p><a href="<?= $authorize_link ?>"><?php echo $authorize_link ?></a></p>
    <?php endif ?>

    <?php if (isset($ticket)): ?>
        <pre><?php print_r($ticket) ?></pre>
    <?php endif ?>

    <?php if (isset($videos)): ?>
        <pre><?php print_r($videos) ?></pre>
    <?php endif ?>
        
    <?php if (isset($activity)): ?>
        <pre><?php print_r($activity) ?></pre>
    <?php endif ?>   

    <?php if (isset($peopleinfo)): ?>
        <pre><?php print_r($peopleinfo) ?></pre>
    <?php endif ?>  
        
    <?php if (isset($videocomments)): ?>
        <pre>video comments:<?php print_r($videocomments) ?></pre>
    <?php endif ?>          

</body>
</html>
