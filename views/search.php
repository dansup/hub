<?php
require('../inc/autoload.php');
$page = 'Search';
$show_results = false;
$q = (isset($_GET['q'])) ? urlencode(htmlentities($_GET['q'])) : false;
$t = (isset($_GET['t'])) ? urlencode(htmlentities($_GET['t'])) : false;
$p = (isset($_GET['p'])) ? intval($_GET['p']) : 1;
if($q && strlen($q) > 2) {
	$show_results = true;
	$allowed_types = array('node', 'service');
	if(!in_array($t, $allowed_types))
	{
		header('Location: /search?error=invalid+type');
		die();
	}
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
<title><?=$page?> - Hub</title>
<?=$template->getCss('default')?>
<link rel="stylesheet" type="text/css" href="/assets/prod/search.css">
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
    <div class="promo" style="padding-top:30px;">
      <div class="container">
        <div class="text-center">
          <h1>Hub Search</h1>
	<div class="col-xs-12 col-md-6 col-md-offset-3">
	  <form action="/search" method="GET">
	   <div class="input-group input-group-lg">
	      <input type="hidden" name="l" value="<?=$lang?>">    
	      <input type="text" class="form-control" name="q" value="<?=$q?>">
	      <input type="hidden" name="oi" value="<?=sha1($ip)?>">
	      <input type="hidden" name="ts" value="<?=time()?>">
	      <span class="input-group-btn">
	        <button class="btn btn-success" type="submit">Search!</button>
	      </span>
	    </div>
	    <div class="form-group">
	    	<div class="radio-inline">
	    		<label>
	    			<input type="radio" name="t" id="t" value="node" checked>
	    			Node
	    		</label>
	    	</div>
	    	<div class="radio-inline">
	    		<label>
	    			<input type="radio" name="t" id="t" value="service">
	    			Service
	    		</label>
	    	</div>
	    </div>
	  </form>
	</div>
        </div>
      </div>
    </div>
<div class="container" role="main" style="padding:40px 0;">
<div class="row">
<div class="col-xs-12 col-md-8 col-md-offset-2">
<?php if($show_results) { 
$search->searchQuery($q, $p, $t, null); ?>
<?php } ?>
</div>
</div>


</div>
<?=$template->getJs('basic')?>
</body>
</html>
