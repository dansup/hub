<?php
require_once(__DIR__.'/../../Config/App.php');
require_once(__DIR__.'/../../../vendor/autoload.php');


use \App\Models\Node;
use \App\Models\Nmapper;

$napi = new Node();
$nl = $napi->getPingList();
$res = 0;
foreach($nl as $ip) {
    $res = $napi->pingNode($ip, 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5', 3000);
    $res = ($res !== false) ? $res++ : null;
    $napi->setPeers($ip, 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5');
}


echo json_encode($res);