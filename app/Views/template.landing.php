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
<body role="document">
	<div class="container">
		<div class="header clearfix">
			<nav>
				<ul class="nav nav-pills pull-right">
					<li role="presentation"<?=$this->uri('/nodes', 'class="active"')?>><a href="/nodes">Nodes</a></li>
					<li  <?=$this->uri('/services', 'class="active"')?>><a href="/services">Services</a></li>
					<li  <?=$this->uri('/me', 'class="active"')?>><a href="/me">My Node</a></li>
				</ul>
			</nav>
			<h3 class="text-muted"><a href="/">Hub</a></h3>
		</div>
<div class="row marketing">
		<?=$this->section('content')?>
</div>
		<footer class="footer">
			<p class="text-muted text-right">Hub v <a href="https://github.com/dansup/hub">0.5</a>. Created by <a href="https://projectmeshnet.org">Project Meshnet</a>.</p>
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