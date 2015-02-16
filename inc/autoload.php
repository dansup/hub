<?php
require_once('config.inc.php');
require_once('page.inc.php');
require_once("capi/b.inc.php");
require_once("capi/c.inc.php");

require_once("core.inc.php");
require_once("inet6.php");
require_once('tmpl.inc.php');

$my_ip = filter_var($_SERVER['REMOTE_ADDR']);
$ip = filter_var($_SERVER['REMOTE_ADDR']);
$lang = 'en-US';