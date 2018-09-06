<?php 
    // needed for .env file
    require_once __DIR__ . '/vendor/autoload.php';
    // require config for database
    require_once ('config.php');
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
    // getting elements from .env file
    define('PARAMS', array("apikey" => getenv('API_KEY'),
    "userkey" => getenv('USER_KEY'),
    "username" => getenv('USERNAME')));
    // assigning base url so if it changes it is in one place
    define("base_url", "https://api.thetvdb.com/");

    // start session
    session_start();

    // curl request to get token
    function curlPost($url, $params){
        // turn parameters into json to send
        define("data_string", json_encode($params));
        // settings for curl
        $defaults = array(
            CURLOPT_URL => base_url . $url,            
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => data_string,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        );
        // initiate and execute curl
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $data = json_decode(curl_exec($ch), true);
        // save token in session to use
        $_SESSION['token'] = $data['token'];
        // end the curl request
        curl_close($ch);
    }
        
    // curl request for data
    function curlGet($url, $number, $array = array()){
        global $connection;
        // settings for curl
        $defaults = array(
            CURLOPT_URL => base_url . $url,            
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $_SESSION['token'],
            ),
        );
        // initate and exectue curl
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $data = json_decode(curl_exec($ch), true);
        // end curl
        curl_close($ch);
        // start path to get all data
        if($number == 0){
            // the inital request in order to get the show's id
            $_SESSION['id'] = $data['data'][0]['id'];
            // check if show is already in database
            $id = $data['data'][0]['id'];
            $query1 = "SELECT * FROM Shows WHERE id = $id";
            $result = $connection->query($query1);

            if ($result->num_rows > 0) {
                // output data of each row
                foreach($result as $data) {
                    echo json_encode($data);
                }
                return;
            } else {
                // initiate second curl
                curlGet('series/' . $_SESSION['id'], 1);
            }
        }else if($number == 1){
            // the second request to get all the data on the specific show
            $array["show_data"] = $data;
            // initiate third request
            curlGet('series/' . $_SESSION['id'] . '/episodes', 2, $array);
        }else if($number == 2){
            // third request to get episode data on show
            $array["episode_data"] = $data;
            // send back all the data gathered
            
            addToDatabase($array);
        }

    };

    // where you start in order to see if the token is valid
    function curl($url){
        if(!isset($_SESSION['token'])){
            // if there is no token, initiate process to get one
            curlPost('login', PARAMS);
            // after getting token, get the data
            curlGet($url, 0);
        }else{
            // there already is a token, go get the data
            curlGet($url, 0);
        }
    };

    // add shows to own database to save time
    function addToDatabase($data){
        // needs to be global to be accessed inside function
        global $connection;
        // narrowing down data into variables
        $id = $data['show_data']['data']['id'];
        $name = $data['show_data']['data']['seriesName'];
        $network = $data['show_data']['data']['network'];
        $showOverview = $data['show_data']['data']['overview'];
        $showBanner = $data['show_data']['data']['banner'];
        $imdb = $data['show_data']['data']['imdbId'];
        $episode1name = $data['episode_data']['data'][0]['episodeName'];
        $episode2name = $data['episode_data']['data'][1]['episodeName'];
        $episode3name = $data['episode_data']['data'][2]['episodeName'];
        $episode1fileName = $data['episode_data']['data'][0]['filename'];
        $episode2fileName = $data['episode_data']['data'][1]['filename'];
        $episode3fileName = $data['episode_data']['data'][2]['filename'];
        $episode1overview = $data['episode_data']['data'][0]['overview'];
        $episode2overview = $data['episode_data']['data'][1]['overview'];
        $episode3overview = $data['episode_data']['data'][2]['overview'];
        $name = $connection->real_escape_string($name);
        $network = $connection->real_escape_string($network);
        $showOverview = $connection->real_escape_string($showOverview);
        $showBanner = $connection->real_escape_string($showBanner);
        $imdb = $connection->real_escape_string($imdb);
        $episode1name = $connection->real_escape_string($episode1name);
        $episode2name = $connection->real_escape_string($episode2name);
        $episode3name = $connection->real_escape_string($episode3name);
        $episode1fileName = $connection->real_escape_string($episode1fileName);
        $episode2fileName = $connection->real_escape_string($episode2fileName);
        $episode3fileName = $connection->real_escape_string($episode3fileName);
        $episode1overview = $connection->real_escape_string($episode1overview);
        $episode2overview = $connection->real_escape_string($episode2overview);
        $episode3overview = $connection->real_escape_string($episode3overview);

        // query to add show to database
        $sql = "INSERT INTO Shows (id, seriesName, overview, network, banner, imdbId, 
        episode1Name, episode1fileName, episode1overview, episode2Name, 
        episode2fileName, episode2overview, episode3Name, episode3fileName, episode3overview)
        VALUES ($id, '$name', '$showOverview', '$network', '$showBanner', '$imdb', '$episode1name', 
        '$episode1fileName', '$episode1overview', '$episode2name', '$episode2fileName', 
        '$episode2overview', '$episode3name', '$episode3fileName', '$episode3overview');";

        if ($connection->query($sql) === TRUE) {
            
            // when successfully created, query again to get just the show
            $query2 = "SELECT * FROM Shows WHERE id = $id";
            $result = $connection->query($query2);
            if ($result->num_rows > 0) {
                // output data of show
                foreach($result as $data) {
                    echo json_encode($data);
                }
            }
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    };

    // autocomplete function
    function getAll($partial){
        // curl to get all options from shorter amount of letters
        $defaults = array(
            CURLOPT_URL => base_url . "search/series?name=" . $partial,            
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $_SESSION['token'],
            ),
        );
        // initate and exectue curl
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $data = json_decode(curl_exec($ch), true);
        // end curl
        curl_close($ch);
        // send all the data back
        echo json_encode($data);
    }

?>