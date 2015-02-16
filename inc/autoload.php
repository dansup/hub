<?php
require_once("/srv/http/hub.hyperboria/inc/inet6.php");
require_once('/srv/http/hub.hyperboria/views/tmpl.inc.php');
require_once("/srv/http/hub.hyperboria/inc/core.inc.php");
require_once("/srv/http/hub.hyperboria/inc/user.inc.php");
require_once("/srv/http/hub.hyperboria/inc/login.lib.php");
$tmpl = new Template();
$user = new User();
$state = $user->isLoggedIn();
$tmpl = new Template();
$csrf = new Csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
