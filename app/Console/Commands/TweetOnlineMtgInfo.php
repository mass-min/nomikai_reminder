<?php

namespace App\Console\Commands;

use App\PostedTweet;
use App\Services\TwitterService;
use App\Services\SlackService;
use Illuminate\Console\Command;

class TweetOnlineMtgInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweet:online-mtg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Twitterに流れたオンラインMTG情報をキャッチしツイートします';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slackService = new SlackService();
        $twitterService = new TwitterService();
        $zoomTweets = $twitterService->getTweetOfZoomMtg();
        $skypeTweets = $twitterService->getTweetOfSkypeMtg();
        $tweets = array_merge($zoomTweets, $skypeTweets);

        foreach($tweets as $tweet) {
            $twitterService->tweetOnlineMtgInfo($tweet);
            $slackService->tweetOnlineMtgInfo($tweet['text']);
        }
        PostedTweet::create(['tweet_id' => $tweets[count($tweets) - 1]['id']]);;
    }
}
