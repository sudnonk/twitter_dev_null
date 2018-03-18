<?php
    require "vendor/autoload.php";
    require "config.php";

    use Abraham\TwitterOAuth\TwitterOAuth;

    $connection      = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_SECRET);

    $latest_tweet_id = "975248769649278976";
    while(true) {
        $content         = $connection->get("statuses/user_timeline", array("user_id" => "3164304848", "count" => "1","since_id"=>$latest_tweet_id));
        if(isset($content[0])){
            $latest_tweet_id = (string)$content[0]->{"id"};
            var_dump($content[0]->{"id"});
            $delete = $connection->post("statuses/destroy", array("id" => $latest_tweet_id));
        }
        sleep(2);
    }