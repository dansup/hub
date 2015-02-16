<?php
require_once("/srv/http/hub.hyperboria/inc/core.inc.php");
require_once("/srv/http/hub.hyperboria/inc/inet6.php");
die('Missing Auth Token.');
$ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

// hostname
$hostname = (isset($_REQUEST['hostname']) && !empty($_REQUEST['hostname']) && strlen($_REQUEST['hostname']) > 4 && strlen($_REQUEST['hostname']) < 29) ? $node->postUpdate('hostname', htmlentities($_REQUEST['hostname']), $ip) : false;
// ownername
$ownername = (isset($_REQUEST['ownername']) && !empty($_REQUEST['ownername']) && strlen($_REQUEST['ownername']) >= 4 && strlen($_REQUEST['ownername']) < 12) ? $node->postUpdate('ownername', htmlentities($_REQUEST['ownername']), $ip) : false;
// public_key
$public_key = (isset($_REQUEST['public_key']) && !empty($_REQUEST['public_key']) && strlen($_REQUEST['public_key'])  === 54) ? $node->postUpdate('public_key', htmlentities($_REQUEST['public_key']), $ip) : false;
// country
$country = (isset($_REQUEST['country']) && !empty($_REQUEST['country']) && strlen($_REQUEST['country']) > 4 && strlen($_REQUEST['country']) < 12) ? $node->postUpdate('country', htmlentities($_REQUEST['country']), $ip) : false;


header('Content-Type: application/json');
if($hostname OR $ownername OR $public_key OR $country) {

    http_response_code(200);
    echo json_encode(['http_response_code'=>200,'success'=>true], JSON_PRETTY_PRINT);

}
else {
        // http_response_code(503);
         http_response_code(400);
        echo json_encode(['http_response_code'=>400,'error'=>true,'error_description'=>'Bad Request.'], JSON_PRETTY_PRINT);

}

?>