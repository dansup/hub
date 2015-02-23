<?php

use League\Event\Emitter;
use League\Event\Event;

$emitter = new Emitter;

$emitter->addListener('capi.ping.node', function (Event $event, $ip) {
        $capi = new CjdnsApi(CJDNS_API_KEY);
        $ping_r[] = $capi->call("RouterModule_pingNode",array("path"=>$ip));
        if(@$ping_r[0]['result'] == "pong")
        {
        	return $ping_r;
        }
});
$emitter->addListener('capi.nodestore.node', function (Event $event, $ip) {
        $capi = new CjdnsApi(CJDNS_API_KEY);
        $ping_r[] = $capi->call('NodeStore_nodeForAddr', array("ip"=>$ip));
        if(@$ping_r[0]['result'] == "pong")
        {
        	return $ping_r;
        }
});
$emitter->addListener('capi.ping.peers', function (Event $event, $ip) {
        $capi = new CjdnsApi(CJDNS_API_KEY);
        $ping_r[] = $capi->call("RouterModule_getPeers",array("path"=>$ip, "timeout"=>20000)),;
        if(@$ping_r[0]['result'] == "pong")
        {
        	return $ping_r;
        }
});