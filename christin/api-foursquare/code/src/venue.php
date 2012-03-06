<?php
require_once("../lib/FoursquareAPI.class.php");
$location = array_key_exists("location", $_GET) ? $_GET['location'] : "Montreal, QC";
?>
<!doctype html>
<html>
    <head>
        <title>PHP-Foursquare :: Unauthenticated Request Example</title>
    </head>
    <body>
        <h1>Basic Request Example</h1>
        <p>
            Search for venues near...
        <form action="" method="GET">
            <input type="text" name="location" value="<?php (isset($_GET['location']) ? $_GET['location'] : '' ); ?>" />
            <input type="submit" value="Search!" />
        </form>
        <p>Searching for venues near <?php echo $location; ?></p>
        <hr />
        <?php
        // Set your client key and secret
        $client_key = "10KUVECURKCQ4LSOTSZPS3U0ZUB5RBJB4FALVSLD4TPEMI35";
        $client_secret = "DBJOAI2BP53JKZW00UTOCUYHGEAAGV0UNBNDRAYJ05CRJCDC";
        // Load the Foursquare API library
        $foursquare = new FoursquareAPI($client_key, $client_secret);

        // Generate a latitude/longitude pair using Google Maps API
        list($lat, $lng) = $foursquare->GeoLocate($location);

        // Prepare parameters
        $params = array("ll" => "$lat,$lng");

        // Perform a request to a public resource
        $response = $foursquare->GetPublic("venues/search", $params);
        $venues = json_decode($response);


        //var dump of the venue response which we gets
        echo "<pre>";
        print_r($venues->response->groups);
        echo "</pre>";
        exit;
        foreach ($venues->response->groups as $group):
            ?>

            <h2><?php echo $group->name; ?></h2>
            <ul>
                <?php
                foreach ($group->items as $venue):
                    ?>
                    <li>
                        <?php
                        echo $venue->name;
                        if (property_exists($venue->contact, "twitter")) {
                            echo " -- follow this venue <a href=\"http://www.twitter.com/{$venue->contact->twitter}\">@{$venue->contact->twitter}</a>";
                        }
                        ?>

                    </li>
                <?php endforeach; ?>
            </ul>

        <?php endforeach; ?>
    </body>
</html>
