<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

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
     * @return array
     */
    public function getTweetOfOnlineMtg(): array
    {
        $result = $this->twitter->get("search/tweets", [
            "q" => "\"zoom.us\"",
            "lang" => "ja",
            "count" => 5,
            "URL" => "https://zoom.us",
            "result_type" => "recent",
        ]);

        $tweetTexts = [];
        foreach ($result as $tweets) {
            foreach ($tweets as $tweet) {
                if (!isset($tweet->text) || !isset($tweet->entities->urls[0])) {
                    continue;
                }
                $tweetTexts[] = $tweet->text . "\n" . $tweet->entities->urls[0]->expanded_url . "\n";
            }
        }
        return $tweetTexts;
    }

    /**
     * @param string $tweet
     * @return array|object
     */
    public function tweetOnlineMtgInfo(string $tweet)
    {
        // ツイート文言
        $text = "こんなオンラインMTGを発見しました！\n" . $tweet;

        //ツイートする
        return $this->twitter->post("statuses/update", ['status' => $text]);
    }
}