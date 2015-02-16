<?php
require('../inc/autoload.php');
$page = 'About';
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
<div class="promo">
      <div class="container">
        <div class="text-center">
          <h1>About Hub</h1>
          <p class="lead">Hub is a community utility powered by the Hyperboria API</p>
        </div>
      </div>
    </div>
    <div class="container" role="main" style="padding-top:40px;">
      <div class="col-xs-12 col-md-10 col-md-offset-1"> 
        <p class="lead">Hub was created by <a href="http://socialno.de/derp">derp</a> in June 2014 as a means to collect and use network information for various Appnode services.</p>
        <p>It was realized the potential value to the public, so we created a public facing API and website with full access to the data. As we move forward with the version 0.1 beta release, we look forward to seeing Hub evolve into a Hyperboria community utility.</p>
      </div>
    </div>
    <?=$template->getJs('basic')?>
  </body>
</html>
