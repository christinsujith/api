<?php
	/**
	 * Class Tumblr.
	 * @author christin
         * created date 24-02-2012
         * updated date 24-02-2012
	 */

class Tumblr
{
    private static $tum_oauth;

    private function __construct()
    {
    }

    public static function singleton($consumer_key, $consumer_secret)
    {
        if (!isset(self::$tum_oauth)) {
            // Create instance of TumblrOAuth.
            // It'll need our Consumer Key and Secret as well as our Request Token and Secret
            self::$tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $_SESSION['request_token'], $_SESSION['request_token_secret']);
        }
        return self::$tum_oauth;
    }


}
?>
