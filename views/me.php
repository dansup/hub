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
$node = new node();
$csrf = new Csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
$resp = false;
// Generate Random Form Name
$form_names = $csrf->form_names(array('hostname', 'ownername', 'nodepublickey', 'location'), false);

$p_title = 'My Node';
$m = null;
$get_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
$my_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
$cjd_ip = substr($get_ip, 0,2);
if(strlen($get_ip) < 39)
{
	$get_ip = $node->inet6_expand($get_ip);
}

if($node->get($get_ip) && $get_ip !== 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5')
{
	$nowner = !empty($node->nodeOwner()) ? $node->nodeOwner() : "Unknown";
	$node_latency = !empty($node->nodeLatency() )? $node->nodeLatency() : "?";
	$node_version = !empty($node->nodeVersion()) ? $node->nodeVersion() : "?";
	@$peer_count = !empty($node->peerCount()) ? $node->peerCount() : "?";
	$node_hostname = !empty($node->nodeHostname()) ? htmlentities($node->nodeHostname()) : "Hostname not set";
	$known = true;
	$nodepublickey = !empty($node->publicKey()) ? filter_var($node->publicKey()) : "Unknown";
	$node_location = !empty($node->location()) ? filter_var($node->location()) : "Unknown";
}
else
{
	$nowner = "Unknown";
	$node_version = "?";
	$peer_count = "?";
	$node_hostname = "Hostname not set.";
	$known = false;
	$nodepublickey = 'Unknown';
	$node_location = 'Unknown';
}
if(isset($_POST))
{
	if($csrf->check_valid('post')) {
		$hostname = (isset($_POST[$form_names['hostname']]) && strlen($_POST[$form_names['hostname']]) > 3) ? filter_var($_POST[$form_names['hostname']]) : false;
		$ownername = (isset($_POST[$form_names['ownername']]) && strlen($_POST[$form_names['ownername']]) > 3) ? filter_var($_POST[$form_names['ownername']]) : false;
		$nodepublickey = (isset($_POST[$form_names['nodepublickey']]) && strlen($_POST[$form_names['nodepublickey']]) > 3) ? filter_var($_POST[$form_names['nodepublickey']]) : false;
		$location = (isset($_POST[$form_names['location']]) && strlen($_POST[$form_names['location']]) > 3) ? filter_var($_POST[$form_names['location']]) : false;
		if($hostname !== false)
		{
			if($node->updateHostname($get_ip, $hostname))
			{
				header('Location: /me');
			}
			else
			{
				$m = "error";
			}
		}
		if($ownername !== false)
		{
			if($node->updateOwnername($get_ip, $ownername))
			{
				header('Location: /me');
			}
			else
			{
				$m = "error";
			}
		}
		if($nodepublickey !== false)
		{
			if($node->updateNodepublickey($get_ip, $nodepublickey))
			{
				header('Location: /me');
			}
			else
			{
				$m = "error";
				throw new Exception($nodepublickey);
			}
		}
		if($location !== false)
		{
			if($node->updateNodelocation($get_ip, $location))
			{
				header('Location: /me');
			}
			else
			{
				$m = "error";
				throw new Exception(var_dump($location));
			}
		}
	}
	$form_names = $csrf->form_names(array('hostname', 'ownername', 'nodepublickey', 'location'), true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="/favicon.ico">
<title><?=$p_title?> - Hub</title>
<?=$tmpl->getCss()?>
<style type="text/css">
a, a:hover, a:active {
  color: #F9690E;
}
.nav > li > a:hover, .nav > li > a:focus {
    text-decoration: none;
    background-color: #FEE1CF;
}

.nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
    color: #FFF;
    background-color: #F9690E;
}
</style>
</head>
<body role="document">
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="/"><?=appName?></a>
</div>
<div class="navbar-collapse collapse">
<ul class="nav navbar-nav">
<?=$tmpl->getNav(null, $p_title)?>
</ul>
</div>
</div>
</div>
<div class="promo promo-nodes">
<div class="container">
<div class="text-center">
<h1><?=$node_hostname?></h1>
<p class="lead"><a href="/node/<?=$get_ip?>"><?=$get_ip?></a></p>
</div>
</div>
</div>
<div class="container" role="main">
<div class="col-xs-12 col-md-8 col-md-offset-2 centered-pills" style="padding:40px 0;">
<ul class="nav nav-pills">
<li role="presentation"><a href="/node/browse">All</a></li>
<li role="presentation"><a href="/node/browse?ob=3">Recently Added</a></li>
<li role="presentation"><a href="/node/search">Search</a></li>
<li role="presentation"><a href="/node/<?=filter_var($_SERVER['REMOTE_ADDR'])?>">My Node</a></li>
<li role="presentation" class="active"><a href="/me">Edit Node</a></li>
</ul>
<hr>
</div>
<div class="col-xs-12 col-md-8 col-md-offset-2">
<?php if($known) { ?>
<div class="panel panel-default">
<div class="panel-heading"><h3>My Node</h3></div>
<div class="panel-body">
<?=$m?>
<form method="POST" class="form-horizontal">
<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
<div class="form-group">
<label class="col-sm-3 control-label">Hostname</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$node_hostname?>" value="" type="text" name="<?=$form_names['hostname'];?>" />
</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Owner Name</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$nowner?>" value="" type="text" name="<?=$form_names['ownername'];?>" />
</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Node Public Key</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$nodepublickey?>" value="" type="text" name="<?=$form_names['nodepublickey'];?>" />
</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Country</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$node_location?>" value="" type="text" name="<?=$form_names['location'];?>" />
</div>
</div>
<hr>
<div class="form-group text-center">
<button class="btn btn-success" type="submit">Update</button>
</div>
</form>
</div>
</div>
</div>
<?php } else { ?>
<div class="panel panel-default">
<div class="panel-heading"><h3>My Node</h3></div>
<div class="panel-body">
<div class="alert alert-danger">
<p>We have no information on your node, please fill out the following information to be added to the NodeDB.</p>
</div>
<hr>
<form method="POST" class="form-horizontal">
<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
<div class="form-group">
<label class="col-sm-3 control-label">Hostname</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$node_hostname?>" value="" type="text" name="<?=$form_names['hostname'];?>" />
</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Owner Name</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$nowner?>" value="" type="text" name="<?=$form_names['ownername'];?>" />
</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Node Public Key</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$nodepublickey?>" value="" type="text" name="<?=$form_names['nodepublickey'];?>" />
</div>
</div>
<div class="form-group">
<label class="col-sm-3 control-label">Country</label>
<div class="col-sm-9">
<input class="form-control" placeholder="<?=$node_location?>" value="" type="text" name="<?=$form_names['location'];?>" />
</div>
</div>
<hr>
<div class="form-group text-center">
<button class="btn btn-success" type="submit">Update</button>
</div>
</form>
</div>
</div>

</div>
<?php } ?>
</div>

<?=$tmpl->getJs('basic')?>
</body>
</html>
