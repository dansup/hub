<?php
require('../inc/autoload.php');
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
<body role="document" class="services">
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
          <h1>Tags</h1>
          <p class="lead">Hashtags are categories associated with services.</p>
        </div>
      </div>
    </div>
<div class="container" role="main">
<div class="col-xs-12 col-md-8 col-md-offset-2 centered-pills" style="padding:40px 0;">
<ul class="nav nav-pills nav-pills-services">
  <li role="presentation"><a href="/services">All</a></li>
  <li role="presentation"><a href="/services/recent">Recently Added</a></li>
  <li role="presentation"><a href="/services/search">Search</a></li>
  <li role="presentation" class="active"><a href="/services/tags">Tags</a></li>
  <li role="presentation"><a href="/services/me">My Services</a></li>
  <li role="presentation"><a href="/services/add">Add New</a></li>
</ul>
<hr>
</div>
<div class="row">

<div class="col-xs-12 col-md-8 col-md-offset-2">
<p class="lead"><a href="/services/search?tag=website" class="label label-default">#website</a></p>
</div>

</div>


</div>
<?=$template->getJs('basic')?>
</body>
</html>
