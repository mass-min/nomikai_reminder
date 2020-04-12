<?php
namespace App\Services;

class TwitterService
{
    /**
     * @return array
     */
    public function getTweetOfOnlineMtg(): array
    {
        // オンラインMTGのURLを含むツイートを取得してくる

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
    public function tweetOnlineMtgInfo (string $tweet)
    {
        // ツイートする
        echo $tweet . "\n";
    }
}