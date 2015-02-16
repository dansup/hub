<?php
require('../inc/autoload.php');
$id = isset($_GET['id']) ? filter_var($_GET['id']) : false;
if(!$id) {
header('Location: /services');
exit();
}
$serv = $services->getService($id);
$s_firsts = $serv['date_added'];
$s_lasts = $serv['last_seen'];
$s_name = htmlentities($serv['name']);
$s_ptype = $serv['type'];
switch ($s_ptype) {
case 1:
$s_type = 'Website';
break;

default:
$s_type = 'Unknown';
break;
}
switch ($serv['state']) {
case 0:
$s_state = '<span class="label label-danger">Unverified</span>';
break;

case 1:
$s_state = '<span class="label label-danger">Awaiting Verification</span>';
break;

case 2:
$s_state = '<span class="label label-success">Verified</span>';
break;

default:
$s_state = '<span class="label label-danger">Unverified</span>';
break;
}
$s_desc = ($serv['description'] == null) ? 'No description available.' : htmlentities($serv['description']);
$s_sdesc = htmlentities($serv['short_description']);
$s_ip = htmlentities($serv['ip']);
$s_uri = filter_var($serv['uri']);
$page = 'Services';
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
<?=$template->getCss()?>
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
<div class="promo promo-services">
<div class="container">
<div class="text-center">
<h1><?=$s_name?> </h1>
<p class="lead"><?=$s_sdesc?></p>
</div>
</div>
</div>
<div class="container" role="main">
<div class="col-xs-12 col-md-8 col-md-offset-2" style="padding:40px 0;">
<div class="col-xs-12">
<div class="panel panel-default">
<div class="panel-body">
<p class="lead"><strong>URI:</strong> <a href="<?=$s_uri?>" rel="nofollow"><?=$s_uri?></a></p>
<p><strong>Description:</strong> <?=$s_desc?></p>
<p><strong>Status:</strong> <?=$s_state?></p>
</div>

</div>
</div>
<div class="col-xs-12">
<div class="panel panel-default">
<div class="panel-body"><br>
<table class="table table-striped table-bordered">
<tbody>
<tr>
<td>
<strong>IP:</strong> <span><?=$s_ip?></span>
</td>
</tr>
<tr>
<td>
<strong>First Seen:</strong> <span><time class="timeago" datetime="<?=$s_firsts?>"><?=$s_firsts?></time></span>
</td>
</tr>
<tr> 
<td>
<strong>Service Type:</strong> <span><?=$s_type?></span>
</td>
</tr>
</tbody>
</table>
</div>

</div>
</div>
</div>
</div>
<?=$template->getJs('basic')?>
</body>
</html>
