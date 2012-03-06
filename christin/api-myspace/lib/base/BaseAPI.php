<?php
require_once("Common.php");
require_once("Util.php");
require_once "HTTP/Request.php"; // FROM PEAR.php.net -- install by command $> pear install HTTP_Request

class BaseAPI {
    protected $resource_base = "";
    protected $resource_uri = "";
    protected $api_version = "";
    
    protected $response_type = "";
    protected $output_array = false;
    protected $object_model = false;
    
    public function __construct() {
        $output_array = false;
        $object_model = false;
    }
    
    //Properties set-response-type
    public function set_response_type($response_type) {
        $this->response_type = $response_type;
    }
    
    //Properties get-response-type
    public function get_response_type() {
        return $this->response_type;
    }
    
    //Properties set-output-array
    public function set_output_array($output_array) {
        $this->output_array = $output_array;
    }
    
    //Properties get-output-array
    public function get_output_array() {
        return $this->output_array;
    }
    
    
    //Properties set-api-version
    public function set_api_version($api_version) {
        $this->api_version = $api_version;
    }
    
    //Properties get-api-version
    public function get_api_version() {
        return $this->api_version;
    }
    
    //DO - get
    public function _do_get($resource_request, $headers) {
        if (function_exists('curl_init')) {
            $curl = curl_init();
            
            //TODO: add headers
            //curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_URL, $resource_request);
            curl_setopt($curl, CURLOPT_USERAGENT, CommonConstants::$LIB_NAME . ' ' . CommonConstants::$LIB_VERSION . ' (curl) ' . phpversion());
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            
            $response_content = curl_exec($curl);
            $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            //echo $response_code;

            if ($response_code && $response_code >= 400 ) {
                throw new Exception($response_code . ' ' . $response_content);
            }
            
            curl_close($curl);
        } else {
            //var_dump($resource_request);
            
            $request =& new HTTP_Request($resource_request);
            
            //echo "\n".$request->getUrl();
            
            //$request->setMethod(HTTP_REQUEST_METHOD_GET;
            $request->addCookie("MYUSERINFO", "MIICpQYKKwYBBAGCN1gDm6CCApUwggKRBgorBgEEAYI3WAMBoIICgTCCAn0CAwIAAQICZgMCAgDABAgotGsFTUOvAAQQHOnlC1MFRhij1jgotqie%2fQSCAlCT%2ftixDTohC2Tfd4C5QSBNGSGC%2bvmnZJVxOISWm1O3Dx26XxLBq7Ee7B33LY0QTzanl3RW89j41NtrzvSXYDUvrgtyM1%2bJXalAfwUGg2Mv2GWr%2bpATySLWuXGEgVtbVYzxvu9MLkaDNs%2bbe3wIHY%2be9%2blutTbNAI41ZjuYTAUZIAg5y8wipBAVfxl7jUiHtDQz4q4GhLMk6J8QytOwAJ8rCtACpUtovJhG91JhA0TLXYGkOzGvdETHd4szsU23dWYh%2fs4TIceGW0mgqfwdNzQRg5vj0ApnBkHGeNm%2b%2fAOxJsCZYFvoNgGAbLfG1LN%2fc545kwMj4zOKmilijxnU1so6Nr8sKgE%2bBpMhwjSLZ0ya6uOsHvz694WAxIzS9WBCb17DoRib3SckcFoCuE1GVKyN1V23HRzyFZDv%2bYDQPlkLT2bSJn55oUsWHk%2fj4onw1FwhBSKTsTlXbGCzlhLK3HbFSkJtxLz%2b2u2EPwnKQVcLrNwnTKq9RUlAOGVWsS0AcnNYkius2zGsg3odA2K46vXR%2fF2EWrj9iOf82p6vNumdHh7lyxrfV%2b3OkYEQTG9QqZ0A4zwcABoWmS9lwiVh%2b1Isi%2fSk2z3kY16NOpb2G4knCmdtTAj5DC8N%2bg3uHIJ3F%2bcDQIweNwkdf9qzie7VzZhS%2bCxtWRi0p3k%2feRZkhUGUhiMj1wscNV5hTl%2fgRHWhks2LpbMjVINXB%2b2Soi6gQcm97t5EMMkXqzGQmEO3FYo0XyW0Zt41hwVbUBgIRdGs2D3KBl4huFAvzTH4%2fD%2bEXZE8");
            
            if ($headers != null) {
                foreach($headers as $header_name => $header_value) {
                    $request->addHeader($header_name, $header_value);
                }                
            }
            
            if (!PEAR::isError($request->sendRequest())) {
                $response_content = $request->getResponseBody();
            } else {
                die($request->getResponseBody());
            }
            
            //echo $client->status;
            
            if ($request->getResponseCode() >= 400) {
                throw new Exception($request->getResponseCode() . ' ' . $response_content);
            }            
        }
        
        if ($this->output_array) {
            $response_content = ArrayUtil::to_array($response_content, $this->get_response_type());
        }
        
        return $response_content;
    }

    //DO - post -- $post_data should be in the following format array('name' => 'Some Name', 'email' => 'email@example.com'));
    public function _do_post($resource_request, $post_data, $headers) {
        if (function_exists('curl_ini')) {
            $curl = curl_init();
            
            $header_list = null;
            
            if ($headers != null) {
                foreach($headers as $header_name => $header_value) {
                    if ($header_list == null) {
                        $header_list = array($header_name . ": " . $header_value);
                    } else {
                        $header_list = array_merge($header_list, $header_name . ": " . $header_value);
                    }
                }                
            }
            
            //curl_setopt($curl, CURLOPT_HEADER, 0);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, $header_list);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: PUT"));
            curl_setopt($curl, CURLOPT_URL, $resource_request);
            curl_setopt($curl, CURLOPT_USERAGENT, CommonConstants::$LIB_NAME . ' ' . CommonConstants::$LIB_VERSION . ' (curl) ' . phpversion());
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, HttpUtil::convert_post_data($post_data));
            
            $response_content = curl_exec($curl);
            $reponse_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            curl_close($curl);
        } else {
            $request =& new HTTP_Request($resource_request);
            
            $request->setMethod(HTTP_REQUEST_METHOD_POST);

            if ($headers != null) {
                foreach($headers as $header_name => $header_value) {
                    $request->addHeader($header_name, $header_value);
                }                
            }
            
            if ($post_data != null) {
                foreach($post_data as $post_data_name => $post_name_value) {
                    $request->addPostData($post_data_name, $post_name_value);
                }
            }
            //$request->setBody('<user xsi:schemaLocation="https://api.myspace.com/myspace.xsd" xmlns="api-v1.myspace.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><userid>264730435</userid><uri>http://api.msappspace.com/v1/users/264730435</uri><mood>none</mood></user>');
            
            //$request->setBody("\r\n");
            
            if (!PEAR::isError($request->sendRequest())) {
                $response_content = $request->getResponseBody();
            } else {
                die($response->getMessage());
            }
            
            //echo $client->status;
            
            if ($request->getResponseCode() >= 400) {
                throw new Exception($request->getResponseCode() . ' ' . $response_content);
            }
        }
        
        return $response_content;
    }    

    //DO - put -- $put_data should be in the following format array('name' => 'Some Name', 'email' => 'email@example.com'));
    public function _do_put($resource_request, $put_data, $headers) {
        $put_override_header = array(CommonConstants::$X_HTTP_METHOD_OVERRIDE_HEADER => 'PUT');
        
        if ($headers != null) {
            $headers = array_merge($headers, $put_override_header);
        } else {
            $headers = $put_override_header;
        }
        
        return $this->_do_post($resource_request, $put_data, $headers);
    }    
}
?>