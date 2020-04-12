<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SlackService
{
    public function tweetOnlineMtgInfo(string $tweet)
    {
        // ツイートする
        echo $tweet . "\n";
    }
}
