<?php

/*
  * hub
  * the hyperboria network analytics machine
  * version 0.5
  *
  */

require_once(__DIR__.'/../app/Config/App.php');
require_once(__DIR__.'/../vendor/autoload.php');
require_once(__DIR__.'/../app/bootstrap.php');
require_once(__DIR__.'/../app/routes.php');


$app->run();