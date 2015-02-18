<?php
require_once("/srv/http/hub.hyperboria/inc/core.inc.php");
require_once("/srv/http/hub.hyperboria/inc/inet6.php");

$get_ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
$my_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
$cjd_ip = substr($get_ip, 0,2);

if(empty($get_ip) or strlen($get_ip) > 39 or $cjd_ip !== "fc") {
    $address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
}

if(isset($address)) {
    $ipparts = explode('::', $address, 2);

    $head = $ipparts[0];
    $tail = isset($ipparts[1]) ? $ipparts[1] : array();

    $headparts = explode(':', $head);
    $ippad = array();

    foreach ($headparts as $val) {
        $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
    }

    if (count($ipparts) > 1) {
        $tailparts = explode(':', $tail);
        $midparts = 8 - count($headparts) - count($tailparts);

        for ($i=0; $i < $midparts; $i++) {
            $ippad[] = '0000';
        }

        foreach ($tailparts as $val) {
            $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
        }
    }

    $get_ip = implode(':', $ippad);
}

if ($get_ip === "fcbf:7bbc:32e4:716:bd00:e936:c927:fc14" OR $get_ip === "fcbf:7bbc:32e4:0716:bd00:e936:c927:fc14") {
    $get_ip = 'fc00:a4e7:831d:b0b4:fe4d:3786:4b9a:e50a';
}

$cjdnsApi = new cjdnsApi();

if($router->knownNode($get_ip)==false) {
    header('Content-Type: application/json');
    http_response_code(404);
    $resp = json_encode([
        'response'=>false,
        'response_code'=>404,
        'error_msg'=>'Node not found in our database.'
        ],
        JSON_PRETTY_PRINT
    );
    return $resp;
}

if(strlen($get_ip) < 39) {
    $get_ip = $node->inet6_expand($get_ip);
}

if($node->get($get_ip)) {
    /* */
} else {
    $resp = null;
}


$dis = $cjdnsApi->pingNode($get_ip, $my_ip);
$resp = (array) $node->getNode($get_ip);
header('Content-Type: application/json');

$resp = json_encode([
    'ip'=>$get_ip,
    'generated'=>date('c'),
    'cjdns'=>array(
        'protocol_version'=>intval($resp['version']),
        'public_key'=>null,
        'node_uptime'=>null,
        'date_created'=>null,
        'dedicated_node'=>null,
        'edges'=>null),
    'contact'=>array(
    'nickname'=>$resp['ownername'],
    'location'=>array(
    'lat'=>$resp['lat'],
    'long'=>$resp['lng'],
    'city'=>$resp['city'],
    'province'=>$resp['province'],
    'country'=>$resp['country'])),
    'dns'=>array(),
    'node'=>array(
        'first_seen'=>$resp['first_seen'],
        'last_seen'=>$resp['last_seen']),
    'peer'=>array(
        'accepting_peer_requests'=>null),
        'services'=>array()
    ],
    JSON_PRETTY_PRINT
);

echo $resp;
?>