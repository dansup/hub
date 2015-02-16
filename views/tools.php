<?php
require('../inc/autoload.php');
$page = 'Tools';
$error = false;
$error_msg = null;
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
<title><?=$page?> - Hub</title>
<?=$template->getCss('default')?>

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
<?=$template->getNav(null,$page, null)?>
</ul>
</div>
</div>
</div>
<div class="promo promo-nodes">
<div class="container">
<div class="text-center">
<h1>Tools</h1>
<p class="lead">Hub Network Tools.</p>
</div>
</div>
</div>
<div class="container" role="main">
<div class="col-xs-12 col-md-8 col-md-offset-2" style="padding:40px 0;">

<div class="col-xs-12 col-sm-6" id="dns-lookup">
<div class="panel panel-default">
<div class="panel-heading"><h3>DNS Lookup</h3></div>
<div class="panel-body"><p class="text-center">Perform DNS hostname lookups.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/dns" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>
<div class="col-xs-12 col-sm-6" id="cjdns-version">
<div class="panel panel-default">
<div class="panel-heading"><h3>Cjdns Version Checker</h3></div>
<div class="panel-body"><p class="text-center">Lookup the cjdns protocol version of a hyperboria node.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/version" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>
<div class="col-xs-12 col-sm-6" id="network-map">
<div class="panel panel-default">
<div class="panel-heading"><h3>Network Map</h3></div>
<div class="panel-body"><p class="text-center">A unique perspective of the network, using sigma.js to visualize hyperboria.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/map" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>
<div class="col-xs-12 col-sm-6" id="nodeinfo-generator">
<div class="panel panel-default">
<div class="panel-heading"><h3>NodeInfo Generator</h3></div>
<div class="panel-body"><p class="text-center">Generate a valid NodeInfo.json file for your node.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/nodeinfo-generator" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>
<div class="col-xs-12 col-sm-6" id="nodeinfo-generator">
<div class="panel panel-default">
<div class="panel-heading"><h3>NodeInfo Validator</h3></div>
<div class="panel-body"><p class="text-center">Validate a NodeInfo.json file.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/nodeinfo-validator" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>
<div class="col-xs-12 col-sm-6" id="ping">
<div class="panel panel-default">
<div class="panel-heading"><h3>Ping</h3></div>
<div class="panel-body"><p class="text-center">A simple web-based ping6 utility. Test from 5 different hub nodes.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/ping" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>
<div class="col-xs-12 col-sm-6" id="pulse">
<div class="panel panel-default">
<div class="panel-heading"><h3>Public.key2IP</h3></div>
<div class="panel-body"><p class="text-center">A public key to IPv6 conversion tool.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/publickey2ip" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>
<div class="col-xs-12 col-sm-6" id="pulse">
<div class="panel panel-default">
<div class="panel-heading"><h3>Pulse</h3></div>
<div class="panel-body"><p class="text-center">An advanced ping utility.</p></div>
<div class="panel-footer"><p class="text-center"><a href="/tools/pulse" class="btn btn-sm btn-info">Try</a></p></div>
</div>
</div>


</div>
</div>
<?=$template->getJs('default')?>
</body>
</html>