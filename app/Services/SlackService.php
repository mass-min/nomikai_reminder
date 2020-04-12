<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Http;

class SlackService
{
    protected $channelId;

    public function loadPostChannelId(string $channelName)
    {
        $response = Http::get('https://slack.com/api/channels.list?token=' + env('SLACK_BOT_ACCESSTOKEN'));
        $channelInfos = $response->json();
        $response = Http::get('https://slack.com/api/groups.list?token=' + env('SLACK_BOT_ACCESSTOKEN'));
        $groupInfos = $response->json();
        $this->channelId = $response->json();
        echo json_encode($response->json());
    }

    public function tweetOnlineMtgInfo(array $tweet)
    {
        $response = Http::asForm()->post('https://slack.com/api/chat.postMessage', [
            'channel' => 'G011N7D2TD0',
            'token' => env('SLACK_BOT_ACCESSTOKEN'),
            'text' => $tweet['text'],
        ]);
    }
}
