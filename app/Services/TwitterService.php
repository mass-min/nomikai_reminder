<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\PostedTweet;

class TwitterService
{
    protected $twitter;

    public function __construct()
    {
        $this->twitter = new TwitterOAuth(
            env('TWITTER_CONSUMER_KEY'),
            env('TWITTER_CONSUMER_SECRET'),
            env('TWITTER_ACCESS_TOKEN'),
            env('TWITTER_ACCESS_TOKEN_SECRET')
        );
    }

    /**
     * @param PostedTweet|null $latestPostedTweet
     * @return array
     */
    public function getTweetOfSkypeMtg(PostedTweet $latestPostedTweet = null): array
    {
        if (isset($latestPostedTweet)) {
            $result = $this->twitter->get("search/tweets", [
                "q" => "\"join.skype\"",
                "lang" => "ja",
                "count" => 5,
                "URL" => "https://join.skype",
                "result_type" => "recent",
                "since_id" => (int)$latestPostedTweet->id + 1,
            ]);
        } else {
            $result = $this->twitter->get("search/tweets", [
                "q" => "\"join.skype\"",
                "lang" => "ja",
                "count" => 5,
                "URL" => "https://join.skype",
                "result_type" => "recent",
            ]);
        }

        return $this->getTweetContents($result);
    }

    /**
     * @param PostedTweet|null $latestPostedTweet
     * @return array
     */
    public function getTweetOfZoomMtg(PostedTweet $latestPostedTweet = null): array
    {
        if (isset($latestPostedTweet)) {
            $result = $this->twitter->get("search/tweets", [
                "q" => '"zoom.us" -from:ExplorerMeeting',
                "lang" => "ja",
                "count" => 20,
                "URL" => "https://zoom.us",
                "result_type" => "recent",
                "since_id" => (int)$latestPostedTweet->id + 1,
            ]);
        } else {
            $result = $this->twitter->get("search/tweets", [
                "q" => '"zoom.us" -from:ExplorerMeeting',
                "lang" => "ja",
                "count" => 20,
                "URL" => "https://zoom.us",
                "result_type" => "recent",
            ]);
        }

        return $this->getTweetContents($result);
    }


    /**
     * @param $result
     * @return array
     */
    public function getTweetContents($result): array
    {
        $tweetContents = [];
        foreach ($result as $tweets) {
            foreach ($tweets as $tweet) {
                if (!isset($tweet->text) || !isset($tweet->entities->urls[0])) {
                    continue;
                }
                $tweetContents[] = [
                    'id' => $tweet->id,
                    'text' => $tweet->text . "\n" . $tweet->entities->urls[0]->expanded_url . "\n",
                    'user_screen_name' => $tweet->user->screen_name,
                ];
            }
        }
        return $tweetContents;
    }

    /**
     * @param array $tweet
     * @return array|object
     */
    public function tweetOnlineMtgInfo(array $tweet)
    {
        // ツイート文言
        $text = "こんなオンラインMTGを発見しました！\n"
            . "参加している方: " . $tweet['user_screen_name'] . "\n"
            . $tweet['text'];

        //ツイートする
        try {
            return $this->twitter->post("statuses/update", ['status' => $text]);
        } catch (\Exception $e) {
            var_dump($e->getLine(), $e->getMessage());
        }
    }
}