<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="/favicon.ico">
<title><?=$this->e($title)?> - Hub</title>
<link href="/assets/css/clear-sans.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/core.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/app.css" rel="stylesheet" type="text/css" media="screen" />
<?=$this->section('extra_css')?>

</head>
<body>
<div class="container wrapper">

<nav class="navbar navbar-inverse">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="/">Hub</a>
</div>
<div id="navbar" class="navbar-collapse collapse">
<ul class="nav navbar-nav">
	<li role="presentation"<?=$this->uri('/meshlocals', 'class="active"')?>><a href="/meshlocals">MeshLocals</a></li>
	<li role="presentation"<?=$this->uri('/nodes', 'class="active"')?>><a href="/nodes">Nodes</a></li>
	<li role="presentation"<?=$this->uri('/people', 'class="active"')?>><a href="/people">People</a></li>
	<li role="presentation"<?=$this->uri('/services', 'class="active"')?>><a href="/services">Services</a></li>
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Network <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			<li role="presentation"<?=$this->uri('/maps/meshlocals', 'class="active"')?>><a href="/maps/meshlocals">MeshMap</a></li>
			<li role="presentation"<?=$this->uri('/maps/network', 'class="active"')?>><a href="/maps/network">NodeMap</a></li>
			<li role="presentation"<?=$this->uri('/net/stats', 'class="active"')?>><a href="/net/stats">Network Stats</a></li>
			<li class="divider"></li>
			<li class="dropdown-header">Tools</li>
			<li role="presentation"<?=$this->uri('/tools/nodeinfo.json', 'class="active"')?>><a href="/tools/nodeinfo.json">NodeInfo.json</a></li>
			<li role="presentation"<?=$this->uri('/tools/peer-finder', 'class="active"')?>><a href="/tools/peer-finder">PeerFinder</a></li>
		</ul>
	</li>
</ul>
<ul class="nav navbar-nav navbar-right">
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Me <span class="caret"></span></a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="/user/node">Node Page</a></li>
			<li><a href="/user/peers">Peers</a></li>
			<li><a href="/user/services">Services</a></li>
			<li class="divider"></li>
			<li class="dropdown-header">Settings</li>
			<li><a href="/user/settings">General</a></li>
			<li><a href="/user/settings/api">API</a></li>
			<li><a href="/user/settings/node">Node</a></li>
		</ul>
	</li>
	<li><a href="#">Notifications</a></li>
</ul>
</div>
</div>
</nav>

<?=$this->section('content')?>
<footer class="footer">
<div class="container">
	<div class="col-xs-12 col-md-6">
		<ul class="list-unstyled">
			<li><a href="/site/api">API</a></li>
			<li><a href="/site/about">About</a></li>
			<li><a href="/site/help">Help</a></li>
			<li><a href="/site/report">Report</a></li>
		</ul>
	</div>
	<div class="col-md-6">
		<p class="text-muted text-right">Hub <a href="https://github.com/dansup/hub">v0.6</a></p>
	</div>
</div>
</footer>

</div>

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/jquery.timeago.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery("time.timeago").timeago();
});
</script>
<?=$this->section('extra_js')?>
</body>
</html>