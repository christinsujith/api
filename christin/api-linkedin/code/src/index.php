<?php


/**
 * Session existance check.
 * 
 * Helper function that checks to see that we have a 'set' $_SESSION that we can
 * use for the demo.   
 */ 
function oauth_session_exists() {
  if((is_array($_SESSION)) && (array_key_exists('oauth', $_SESSION))) {
    return TRUE;
  } else {
    return FALSE;
  }
}

try {
  // include the LinkedIn class
  require_once('../lib/linkedin_3.3.0.class.php');
  
  // config variables
  $API_CONFIG = array(
    'appKey'    => 'd8hzjuh4qlta',
    'appSecret' => 'CWbIXOJmVvsSHy9V' 
  );
  
  // constants
  define('CONNECTION_COUNT', 20);
  define('DEMO_GROUP', '4010474');
  define('DEFAULT_JOB_SEARCH', 'Engineering');
  define('DEMO_GROUP_NAME', 'Simple LI Demo');
  define('PORT_HTTP', '80');
  define('PORT_HTTP_SSL', '443');

  // start the session
  if(!session_start()) {
    throw new LinkedInException('This script requires session support, which appears to be disabled according to session_start().');
  }
  
  // set index
  $_REQUEST[LINKEDIN::_GET_TYPE] = (isset($_REQUEST[LINKEDIN::_GET_TYPE])) ? $_REQUEST[LINKEDIN::_GET_TYPE] : '';
  switch($_REQUEST[LINKEDIN::_GET_TYPE]) {
    case 'initiate':
      /**
       * Handle user initiated LinkedIn connection, create the LinkedIn object.
       */
        
      // check for the correct http protocol (i.e. is this script being served via http or https)
      if($_SERVER['HTTPS'] == 'on') {
        $protocol = 'https';
      } else {
        $protocol = 'http';
      }
      
      // set the callback url
      $API_CONFIG['callbackUrl'] = $protocol . '://' . $_SERVER['SERVER_NAME'] . ((($_SERVER['SERVER_PORT'] != PORT_HTTP) || ($_SERVER['SERVER_PORT'] != PORT_HTTP_SSL)) ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['PHP_SELF'] . '?' . LINKEDIN::_GET_TYPE . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';
      $OBJ_linkedin = new LinkedIn($API_CONFIG);
      
      // check for response from LinkedIn
      $_GET[LINKEDIN::_GET_RESPONSE] = (isset($_GET[LINKEDIN::_GET_RESPONSE])) ? $_GET[LINKEDIN::_GET_RESPONSE] : '';
      if(!$_GET[LINKEDIN::_GET_RESPONSE]) {
        // LinkedIn hasn't sent us a response, the user is initiating the connection
        
        // send a request for a LinkedIn access token
        $response = $OBJ_linkedin->retrieveTokenRequest();
        if($response['success'] === TRUE) {
          // store the request token
          $_SESSION['oauth']['linkedin']['request'] = $response['linkedin'];
          
          // redirect the user to the LinkedIn authentication/authorisation page to initiate validation.
          header('Location: ' . LINKEDIN::_URL_AUTHENTICATE . $response['linkedin']['oauth_token']);
        } else {
          // bad token request
          echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
        }
      } else {
        // LinkedIn has sent a response, user has granted permission, take the temp access token, the user's secret and the verifier to request the user's real secret key
        $response = $OBJ_linkedin->retrieveTokenAccess($_SESSION['oauth']['linkedin']['request']['oauth_token'], $_SESSION['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);
        if($response['success'] === TRUE) {
          // the request went through without an error, gather user's 'access' tokens
          $_SESSION['oauth']['linkedin']['access'] = $response['linkedin'];
          
          // set the user as authorized for future quick reference
          $_SESSION['oauth']['linkedin']['authorized'] = TRUE;
            
          // redirect the user back to the demo page
          header('Location: ' . $_SERVER['PHP_SELF']);
        } else {
          // bad token access
          echo "Access token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
        }
      }
      break;

    case 'revoke':
      /**
       * Handle authorization revocation.
       */
                    
      // check the session
      if(!oauth_session_exists()) {
        throw new LinkedInException('This script requires session support, which doesn\'t appear to be working correctly.');
      }
      
      $OBJ_linkedin = new LinkedIn($API_CONFIG);
      $OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
      $response = $OBJ_linkedin->revoke();
      if($response['success'] === TRUE) {
        // revocation successful, clear session
        session_unset();
        $_SESSION = array();
        if(session_destroy()) {
          // session destroyed
          header('Location: ' . $_SERVER['PHP_SELF']);
        } else {
          // session not destroyed
          echo "Error clearing user's session";
        }
      } else {
        // revocation failed
        echo "Error revoking user's token:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
      }
      break;
    default:
      // nothing being passed back, display demo page
      
      // check PHP version
      if(version_compare(PHP_VERSION, '5.0.0', '<')) {
        throw new LinkedInException('You must be running version 5.x or greater of PHP to use this library.'); 
      } 
      
      // check for cURL
      if(extension_loaded('curl')) {
        $curl_version = curl_version();
        $curl_version = $curl_version['version'];
      } else {
        throw new LinkedInException('You must load the cURL extension to use this library.'); 
      }
      ?>
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <title>LinkedIn Crawler</title>
          
  
        </head>
        <body>
           <?php
          if(isset($_SESSION['oauth']['linkedin']['authorized']) === TRUE) {
            // user is already connected
            ?>  
          <?php
          $_SESSION['oauth']['linkedin']['authorized'] = (isset($_SESSION['oauth']['linkedin']['authorized'])) ? $_SESSION['oauth']['linkedin']['authorized'] : FALSE;
          if($_SESSION['oauth']['linkedin']['authorized'] === TRUE) {
            $OBJ_linkedin = new LinkedIn($API_CONFIG);
            $OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
          	$OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);

            	
            	// check if the viewer is a member of the test group
            	$response = $OBJ_linkedin->group(DEMO_GROUP, ':(relation-to-viewer:(membership-state))');
              if($response['success'] === TRUE) {
          		  $result         = new SimpleXMLElement($response['linkedin']);
          		  $membership     = $result->{'relation-to-viewer'}->{'membership-state'}->code;
          		  $in_demo_group  = (($membership == 'non-member') || ($membership == 'blocked')) ? FALSE : TRUE;

			  		  } else {
			  		    // request failed
          			echo "Error retrieving group membership information: <br /><br />RESPONSE:<br /><br /><pre>" . print_r ($response, TRUE) . "</pre>";
			  		  }
		
          } else {
          
          }
         
          if($_SESSION['oauth']['linkedin']['authorized'] === TRUE) {
            // user is already connected
            ?>

            
            <ul>
              <li>Application Key: 
                <ul>
                  <li><pre><?php echo $OBJ_linkedin->getApplicationKey();?></pre></li>
                </ul>
              </li>
            </ul>
            
            <hr />
            
            <h2 id="profile">Your Profile:</h2>
            
            <?php
            $response = $OBJ_linkedin->profile('~:(id,first-name,last-name,picture-url)');
            if($response['success'] === TRUE) {
              $response['linkedin'] = new SimpleXMLElement($response['linkedin']);
              echo "<pre>" . print_r($response['linkedin'], TRUE) . "</pre>";
             // echo "<pre>" . var_dump((array) $response['linkedin'], TRUE) . "</pre>";
            } else {
              // request failed
              echo "Error retrieving profile information:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
            } 
          } else {
         
          }
          ?>
          <br style="clear: both;" />
                     <hr />
            
            <h3 id="networkConnections">Your Connections:</h3>
            
            <?php
            $response = $OBJ_linkedin->connections('~/connections:(id,first-name,last-name,picture-url)?start=0&count=' . CONNECTION_COUNT);
            if($response['success'] === TRUE) {
              $connections = new SimpleXMLElement($response['linkedin']); 
              echo '<pre>';
              print_r($connections);
                  }
                  ?>
                  
                  <br style="clear: both;" />
            <hr />
          
            <h2 id="network">Your Network:</h2>
            
            <h3 id="networkStats">Stats:</h3>
            
            <?php
            $response = $OBJ_linkedin->statistics();
            if($response['success'] === TRUE) {
              $response['linkedin'] = new SimpleXMLElement($response['linkedin']);
              echo "<pre>" . print_r($response['linkedin'], TRUE) . "</pre>"; 
            } else {
              // request failed
              echo "Error retrieving network statistics:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
            }
            ?>  
            <br style="clear: both;" />
            <hr />
            
            <h2 id="peopleSearch">People Search:</h2>
            
            <!--  <p>1st degree connections living in the San Francisco Bay Area (returned in JSON format):</p>-->
            
            <?php
            $OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
            $keywords = (isset($_GET['keywords'])) ? $_GET['keywords'] : "Marketing";
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>#peopleSearch" method="get">
            	Search by Keywords: <input type="text" value="<?php echo $keywords?>" name="keywords" /><input type="submit" value="Search" />
            </form>
            <?php 
            $query    = '?keywords='.$keywords;
            $response = $OBJ_linkedin->searchPeople($query);
            if($response['success'] === TRUE) {
              echo "<pre>" . print_r($response['linkedin'], TRUE) . "</pre>";
            } else {
              // request failed
              echo "Error retrieving people search results:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";                
            
          }
          ?>
          
          <hr />  
                    <hr />
            
            <h3 id="jobsSuggested">Suggested Jobs:</h3>

            <p>Jobs that LinkedIn thinks you might be interested in:</p>
            
            <?php
            $field_selectors = ':(id,customer-job-code,active,posting-date,expiration-date,posting-timestamp,company:(id,name),position:(title,location,job-functions,industries,job-type,experience-level),skills-and-experience,description-snippet,description,salary,job-poster:(id,first-name,last-name,headline),referral-bonus,site-job-url,location-description)';
            $OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
            $response = $OBJ_linkedin->suggestedJobs(":(jobs:(id,company,position:(title)))");
            if($response['success'] === TRUE) {
              $suggested  = new SimpleXMLElement($response['linkedin']);
              $jobs       = $suggested->jobs;
              if((int)$jobs['total'] > 0) {
              	$job   = $jobs->job;
              	$count = 1;
                foreach($job as $job) {
                  $jid          = $job->id;
                  $title        = (string)$job->position->title;
                  $company      = $job->company->name;
                  $poster       = $job->{'job-poster'};
                  $description  = $job->{'description-snippet'};
                  $location     = $job->{'location-description'};
                  ?>
                  <div style=""><span style="font-weight: bold;"><?php echo $title.": ".$company;?></span></div>
                  <?php 
                  if($count == 1) {
                  	$response = $OBJ_linkedin->job((string)$jid, $field_selectors);
                  	if($response['success'] === TRUE) {
                  		echo '<h4>Job Details:</h4>
                            <pre>' . print_r(new SimpleXMLElement($response['linkedin']), TRUE) . '</pre>';
                  	} else {
                  		// request failed
                  		echo "Error retrieving jobs detailed information:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, true) . "</pre>";
                  	}
                  }

                  $count++;
                }
              } else {
                // no jobs suggested
                echo '<div>There are no suggested jobs for you at this time.</div>';
              }
            } else {
              // request failed
              echo "Error retrieving suggested companies:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
            }
            ?>
            
            <hr />
 
            <h3 id="jobsSearch">Job Search (by Relationship):</h3>
            
            <?php
            $OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON); 
            $keywords = (isset($_GET['keywords'])) ? $_GET['keywords'] : DEFAULT_JOB_SEARCH;	
            ?>
      			<form action="<?php echo $_SERVER['PHP_SELF']?>#jobsSearch" method="get">
      				Search by Keywords: <input type="text" name="keywords" value="<?php echo $keywords;?>" /><input type="submit" value="Search" />
      			</form>
			
            <?php
          	$query    = '?keywords=' . urlencode($keywords) . '&sort=R';
          	$response = $OBJ_linkedin->searchJobs($query); 
          	if($response['success'] === TRUE) {
            	echo "<pre>" . print_r($response['linkedin'], TRUE) . "</pre>";
          	} else {
            	// request failed
            	echo "Error retrieving job search results:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";                
          	}
               
             } else {
            // user isn't connected
            ?>
            <form id="linkedin_connect_form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
              <input type="hidden" name="<?php echo LINKEDIN::_GET_TYPE;?>" id="<?php echo LINKEDIN::_GET_TYPE;?>" value="initiate" />
              <input type="submit" value="Connect to LinkedIn" />
            </form>
            <?php
          }
          ?>
        </body>
      </html>
      <?php
      break;
  }
} catch(LinkedInException $e) {
  // exception raised
  echo $e->getMessage();
}

?>