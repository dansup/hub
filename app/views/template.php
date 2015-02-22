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
	<link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/core.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/app.css" rel="stylesheet" type="text/css" />

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
				<a class="navbar-brand" href="/">Hub</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="/browse">Nodes</a></li>
					<li><a href="/services">Services</a></li>
					<li class="divider"></li></ul>
					<form class="navbar-form navbar-left" role="search" action="/search" method="GET">
						<input type="hidden" name="t" value="node"> 
						<input type="hidden" name="l" value="en-US">    
						<div class="form-group">
							<input type="text" class="form-control" name="q" placeholder="search ip, owner, host">
						</div>
					</form>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/me">My Node</a></li>
					</ul>
				</div>
			</div>
		</div>
		<?=$this->section('content')?>

		<footer class="footer">
			<div class="container">
				<p>Hub v<a href="//github.com/dansup/hub">0.1</a></p>
			</div>
		</footer>
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
