<?php
require_once("/srv/http/hub.hyperboria/inc/core.inc.php");
header('Content-Type: application/json');
$api = new Api();
$r = $api->getWebservers();
$api_resp = json_encode($r, JSON_PRETTY_PRINT);
echo $api_resp;
?>