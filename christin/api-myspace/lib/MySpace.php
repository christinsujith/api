<?php

require_once("api/OAuthTokenAPI.php");
require_once("api/PeopleAPI.php");
require_once("api/ActivitiesAPI.php");

class MySpaceAPI {
    public $OAuthToken;
    public $People;
    public $Activities;

    //ctor
    public function __construct($application_key, $application_secret) {
        $this->OAuthToken = new OAuthTokenAPI($application_key, $application_secret);
        $this->People = new PeopleAPI($application_key, $application_secret);
        $this->Activities = new ActivitiesAPI($application_key, $application_secret);
    }
        
    public function set_oauth_token($oauth_token) {
        $this->OAuthToken->set_oauth_token($oauth_token);
    	$this->People->set_oauth_token($oauth_token);
        $this->Activities->set_oauth_token($oauth_token);
    }
    
    public function set_oauth_token_secret($oauth_token_secret) {
        $this->OAuthToken->set_oauth_token_secret($oauth_token_secret);
    	$this->People->set_oauth_token_secret($oauth_token_secret);
        $this->Activities->set_oauth_token_secret($oauth_token_secret);
    }
    
    public function set_output_array($output_array) {
        $this->OAuthToken->set_output_array($output_array);
        $this->People->set_output_array($output_array);
        $this->Activities->set_output_array($output_array);
    }
}
?>