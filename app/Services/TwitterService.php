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
        $result = $this->twitter->get("search/tweets", ["q" => "zoom.us"]);
        dd($result);
        return [
            "オンラインMTGの情報1",
            "オンラインMTGの情報2",
            "オンラインMTGの情報3",
            "オンラインMTGの情報4",
            "オンラインMTGの情報5",
        ];
    }

    /**
     * @param string $tweet
     */
    public function tweetOnlineMtgInfo(string $tweet)
    {
        // ツイートする
        echo $tweet . "\n";
    }
}