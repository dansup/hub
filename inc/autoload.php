<?php

// todo: real autoloading!
require_once('../vendor/autoload.php');

require_once('config.inc.php');
require_once('page.inc.php');

require_once("capi/b.inc.php");
require_once("capi/c.inc.php");

require_once("core.inc.php");
require_once("inet6.php");
require_once('tmpl.inc.php');

// $my_ip is depreciated, use $ip
$my_ip = filter_var($_SERVER['REMOTE_ADDR']);

$ip = (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && substr($_SERVER['REMOTE_ADDR'], 0,2) === 'fc') ? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) : false;

$lang = 'en-US';