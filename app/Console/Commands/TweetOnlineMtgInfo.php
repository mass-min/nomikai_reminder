<?php

namespace App\Console\Commands;

use App\Services\TwitterService;
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
        $twitterService = new TwitterService();
        $tweets = $twitterService->getTweetOfOnlineMtg();

        foreach($tweets as $tweet) {
            $twitterService->tweetOnlineMtgInfo($tweet);
        }
    }
}
