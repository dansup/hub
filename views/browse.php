<?php
require('tmpl.inc.php');
$tmpl = new Template();
$p_title = 'Nodes';
require_once("/srv/http/hub.hyperboria/inc/core.inc.php");
require_once("/srv/http/hub.hyperboria/inc/inet6.php");
// if($_SERVER['REMOTE_ADDR'] !== "fcbf:7bbc:32e4:716:bd00:e936:c927:fc14")
// {
//     die("unauthamorized");
// }
$order_by_options = array(
'options' => array(
'default' => 1, // value to return if the filter fails
// other options here
'min_range' => 1,
'max_range' => 6
)
);
$order_by = isset($_GET['ob']) ? filter_var($_GET['ob'], FILTER_VALIDATE_INT, $order_by_options) : 1;
$page_options = array(
'options' => array(
'default' => 1, // value to return if the filter fails
// other options here
'min_range' => 1,
'max_range' => 20
)
);
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT, $page_options) : 1;
$ip = (isset($_SERVER['REMOTE_ADDR'])) ? filter_var($_SERVER['REMOTE_ADDR']) : false;
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
.browse a {
  color:#666;
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
<h1>Nodes</h1>
<p class="lead">the hyperboria node directory.</p>
</div>
</div>
</div>
<div class="container" role="main">
<div class="col-xs-12 col-md-8 col-md-offset-2 centered-pills" style="padding:40px 0;">
<ul class="nav nav-pills">
<li role="presentation" class="active"><a href="/node/browse">All</a></li>
<li role="presentation"><a href="/node/browse?ob=3">Recently Added</a></li>
<li role="presentation"><a href="/node/search">Search</a></li>
<li role="presentation"><a href="/node/<?=filter_var($_SERVER['REMOTE_ADDR'])?>">My Node</a></li>
</ul>
<hr>
</div>
<div class="col-xs-12 col-md-8 col-md-offset-2 browse">
<?=$router->allKnownNodes($page, $order_by)?>
</div>
</div>
<?=$tmpl->getJs('default')?>
<script type="text/javascript">
$('.dropdown-toggle').dropdown();
$('.browse #node_addr').each(function() {
        $(this).html($(this).html().substr(0, $(this).html().length-4)
        + "<span style='color: #F9690E;'>"
        + $(this).html().substr(-4)
        + "</span>");
});
</script>
</body>
</html>
