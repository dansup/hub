<?php
#!/usr/bin/env php

require_once(__DIR__.'/../Config/App.php');
require_once(__DIR__.'/../../vendor/autoload.php');


$jobby = new \Jobby\Jobby();
use \App\Models\CjdnsApi;
use \App\Models\Node;
use \App\Models\Avatar;
use \App\Models\Nmapper as Nmap;

//  * * * * * cd /srv/http/frontend.hub-dev.hyperboria/public/hub/app/Events && php Jobby.php 1>> /dev/null 2>&1

$jobby->add('dumpTable', array(
    'command' => function() {
        $capi = new CjdnsApi();
        $napi = new Node();
        $pg = 0;
        $dumpt = $capi->call('NodeStore_dumpTable', ['page'=>$pg]);
        $tmax = $dumpt['count'] / 4;
        $tn = [];
        for ($i=0; $i < $tmax; $i++) { 
            $tnodes = $capi->call('NodeStore_dumpTable', ['page' => $i]);
            foreach($tnodes['routingTable'] as $node) {
                $ip = $node['ip'];
                $frag = explode('.', $node['addr']);
                $tn[] = [
                'addr' => $ip,
                'version'=>$frag[0],
                'label'=>$frag[1].'.'.$frag[2].'.'.$frag[3].'.'.$frag[4],
                'key' => $frag[5].'.k',
                'link' => $node['link'],
                'time' => $node['time']
                ];
            }
        }
        $napi->capiDumpTable($tn); 
        return true;
    },
    'schedule' => '*/10 * * * *',
    'debug' => true,
    'recipients' => 'danielsupernault@gmail.com',
    'enabled' => true,
));

$jobby->add('nodePinger', array(
    'command' => function() {
        $napi = new Node();
        //$nl = $napi->getNodeList();
        $pl = $napi->getPingList(100);
        foreach($pl as $ip) {
            $napi->pingNode($ip, 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5', 6000);
            $napi->setPeers($ip, 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5');
        }
        return true;
    },
    'schedule' => '*/5 * * * *',
    'debug' => true,
    'recipients' => 'danielsupernault@gmail.com',
    'enabled' => false,
));

$jobby->add('pingDiscover', array(
    'command' => function() {
        $napi = new Node();
        $nl = $napi->getDiscoveryList();
        foreach($nl as $ip) {
            $napi->pingNode($ip, 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5', 6000);
            $napi->setPeers($ip, 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5');
        }
        return true;
    },
    'schedule' => '*/20 * * * *',
    'debug' => true,
    'recipients' => 'danielsupernault@gmail.com',
    'enabled' => false
));

$jobby->add('nmapper', array(
    'command' => function() {
        $nmap = new Nmap();
        $nodes = $napi->getPingList(30);
        foreach ($nodes as $node) {
            $nmap->newScan($node);
        }
    },
    'debug' => true,
    'recipients' => 'danielsupernault@gmail.com',
    'schedule' => '*/20 * * * *',
    'enabled' => false,
    ));

$jobby->add('avatarGenerator', array(
    'command' => function() {
        $res = (new \App\Models\Event())->avatarGenerator();
        return $res;
    },
    'debug' => true,
    'recipients' => 'danielsupernault@gmail.com',
    'schedule' => '*/30 * * * *',
    'enabled' => true,
));

$jobby->run();
