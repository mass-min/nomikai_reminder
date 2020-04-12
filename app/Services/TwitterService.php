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
     * @return array
     */
    public function getTweetOfSkypeMtg($tweetsMtg): array
    {
        $result = $this->twitter->get("search/tweets", [
            "q" => "\"join.skype\"",
            "lang" => "ja",
            "count" => 5,
            "URL" => "https://join.skype",
            "result_type" => "recent",
        ]);

        $tweetSkypeTexts = [];
        foreach ($result as $tweets) {
            foreach ($tweets as $tweet) {
                if (!isset($tweet->text) || !isset($tweet->entities->urls[0])) {
                    continue;
                }
                $tweetSkypeTexts[] = $tweet->text . "\n" . $tweet->entities->urls[0]->expanded_url . "\n";
            }
        }
        $tweetTexts = array_merge($tweetsMtg, $tweetSkypeTexts);
        return $tweetTexts;
    }

    /**
     * @return array
     */
    public function getTweetOfOnlineMtg(): array
    {
        $result = $this->twitter->get("search/tweets", [
            "q" => '"zoom.us" -from:ExplorerMeeting',
            "lang" => "ja",
            "count" => 20,
            "URL" => "https://zoom.us",
            "result_type" => "recent",
        ]);

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
            $result = $this->twitter->post("statuses/update", ['status' => $text]);
            PostedTweet::create(['tweet_id' => $tweet['id']]);
            return $result;
        } catch (\Exception $e) {
            var_dump($e->getLine(), $e->getMessage());
        }
    }
}