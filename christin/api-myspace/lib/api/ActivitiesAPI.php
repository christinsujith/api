<?php

require_once("../lib/base/Common.php");
require_once("../lib/base/OAuthBaseAPI.php");

class ActivitiesAPI extends OAuthBaseAPI {
    //ctor
    public function __construct($application_key, $application_secret) {
        $this->application_key = $application_key;
        $this->application_secret = $application_secret;
        
        $this->oauth_consumer = new OAuthConsumer($this->application_key, $this->application_secret);
        $this->oauth_token = '';//new OAuthToken(null, null);
        
        $this->api_version = ApiVersionType::$VERSION_V2;
        
        $this->resource_base = CommonConstants::$URL_ROOT_API;
        $this->response_type = ResponseType::$JSON;
    }
    
    //OK
    public function get_activities_current($query_parameters = null) {
        $resource = sprintf("/activities/%s/%s", SelectorType::$ME, SelectorType::$SELF);
        $result = $this->do_get($resource, null, $query_parameters);
        return $result;
    }

    public function post_activities_current($post_data, $headers = null) {
        $resource = sprintf("/activities/%s/%s", SelectorType::$ME, SelectorType::$SELF);
        $result = $this->do_post($resource, $post_data, $headers);
        return $result;
    }

}
?>