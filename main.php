<?php
    require "vendor/autoload.php";
    require "config.php";

    use Abraham\TwitterOAuth\TwitterOAuth;

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_SECRET);

    //ここから先のツイートは削除されます
    $connection->post("statuses/update", array("status" => "起動"));
    $criteria = get_last_tweet_ids($connection, 1)[0];

    while (true) {
        $last_tweet_ids = get_last_tweet_ids($connection, 10, $criteria);
        foreach ($last_tweet_ids as $last_tweet_id) {
            $connection->post("statuses/destroy", array("id" => $last_tweet_id));
        }
        sleep(1.5);
    }

    /**
     * $since_idを含まずに$since_idより新しいツイートのうち最新$num個のツイートを得る
     *
     * @param TwitterOAuth $connection
     * @param int          $num
     * @param string       $since_id
     *
     * @return array 見つからなかったら空配列
     */
    function get_last_tweet_ids(TwitterOAuth $connection, int $num, string $since_id = ""): array {
        $array = array("user_id" => MY_USER_ID, "count" => "$num",);

        if ($since_id !== "") {
            $array["since_id"] = $since_id;
        }

        $contents = $connection->get("statuses/user_timeline", $array);

        $results = array();
        foreach ($contents as $content) {
            $results[] = $content->{"id"};
        }

        return $results;
    }