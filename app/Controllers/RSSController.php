<?php
  /* 
    *   Hub
    *   RSS Controller 
    *
    */
namespace App\Controllers;

use \Suin\RSSWriter\Feed;
use \Suin\RSSWriter\Channel;
use \Suin\RSSWriter\Item;

class RSSController {

    public function getNodeFeed($ip) {
        $feed = new Feed();
        $channel = new Channel();
        $channel
            ->title("{$ip}'s Activity Feed")
            ->description("Channel Description")
            ->url('http://hub.hyperboria.net/node/'.$ip)
            ->language('en-US')
            ->copyright('Copyleft, You.')
            ->pubDate(strtotime(date('c')))
            ->lastBuildDate(strtotime(date('c')))
            ->ttl(20)
            ->appendTo($feed);
        $feed_data = (new \App\Models\Activity())->getFeed('node', $ip, 1);
        if($feed_data['count'] > 0) {
        foreach ($feed_data['data'] as $f) {
        $item = new Item();
        $item
            ->title($f['title'])
            ->description("<div>{$f['body']}</div>")
            ->url('http://frontend.hub-dev.hyperboria.net/node/'.$f['identifier'])
            ->pubDate(strtotime($f['timestamp']))
            ->guid('http://graph.hub.hyperboria.net/o/'.$f['id'], true)
            ->appendTo($channel);
        }
    }

            return $feed;

    }

}