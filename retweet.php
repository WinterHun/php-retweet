<?php
ini_set('display_errors', 1);

//uses https://github.com/J7mbo/twitter-api-php
require_once('TwitterAPIExchange.php');

$settings = array(
    'oauth_access_token' => "",
    'oauth_access_token_secret' => "",
    'consumer_key' => "",
    'consumer_secret' => ""
);

$getfields = array( '#retweet+win+-filter:retweets&result_type=mixed',
                    '#retweet+win+book+-filter:retweets&result_type=mixed',);

$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';

while (true) {
    foreach ($getfields as $getfield) {
         try {

            // Perform the request
            $twitter = new TwitterAPIExchange($settings);
            $twitter2 = new TwitterAPIExchange($settings);
            $response = $twitter->setGetfield('?q='.$getfield)
                         ->buildOauth($url, $requestMethod)
                         ->performRequest();

            $data = json_decode($response);

            foreach ($data->statuses as $value) {
                $response2 = '';

                if (strpos(strtoupper($value->text),'FOLLOW') == false) {
                    print $value->text."\n";
                    $url2 = "https://api.twitter.com/1.1/statuses/retweet/".$value->id.".json";

                    if(!isset($ids[$value->id])){
                        $response2 = $twitter2->setPostfields(array('id' => $value->id))
                             ->buildOauth($url2, 'POST')
                             ->performRequest();
                        var_dump($response2); 
                    }
                    else
                    {
                        print "skip done\n";
                    }

                    $ids[$value->id] = true;
                }
                else
                {
                    print "skip follow\n";
                }
            }

            $save = json_encode($ids);

            print "------------------------------------------------------------------------------------------------\n\n";
            sleep (60);
         } catch (Exception $e) {}
    }
}