<?php
require('tmpl.inc.php');
$tmpl = new Template();
$p_title = 'Home';
$my_ip = filter_var($_SERVER['REMOTE_ADDR']);
require_once('../inc/core.inc.php');
require_once('../inc/user.inc.php');
$state = ($user->isLoggedIn()) ? true : false;
$username = $user->username;
$sn_username = $user->sn_username;
$email = $user->email;
$joined = $user->date_created;
if($sn_username == null OR $sn_username == false OR empty($sn_username))
{
  $sn_username = false;
  $avatar = false;
}
else
{
  $avatar = 'http://socialno.de/'.$sn_username.'/avatar/96';
}
$nav_data = array('username'=>$username, 'avatar'=>$avatar, 'email'=>$email);

$stats = new Network();

$total_nodes = $stats->getTotalNodes();
$avg_version = $stats->getAvgProtocolVersion();
$avg_peers = (int) $stats->getAvgNodePeers() / (int) $stats->total_nodes;
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
    <?=$tmpl->getCss('default')?>
    <style type="text/css">
    .status .panel-title {
        font-size: 72px;
        font-weight: 300;
        color: #fff;
        line-height: 45px;
        padding-top: 20px;
        letter-spacing: -0.8px;
    }
    h1 small{
        font-size: 18px;
        font-weight: bold;
        line-height: 0;
        color: #fff;
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
            <?=$tmpl->getNav(null,null, $nav_data)?>
          </ul>
        </div>
      </div>
    </div>
    <div class="promo" style="margin-top:-5px;height:240px;">
      <div class="container">
        <div class="text-center">
          <h1>Network Stats </h1>
          <p class="lead">Stats collected from the Hyperboria Network..</p>
        </div>
      </div>
    </div>

    <div class="container" style="padding-top:40px;" role="main">
      <div class="row">
        <div class="col-xs-12">
          <div class="panel panel-warning text-center">
            <div class="panel-heading">
              <h4>Nodes</h4>
            </div>
            <div class="panel-body">

              <div class="col-xs-12 col-sm-4">
                <div class="panel status panel-danger" id="total_nodes">
                  <div class="panel-heading">
                    <h1 class="panel-title text-center"><?=intval($total_nodes)?></h1>
                  </div>
                  <div class="panel-body text-center">                        
                    <strong>Total Known Nodes</strong>
                  </div>
                </div>
              </div>

              <div class="col-xs-12 col-sm-4">
                <div class="panel status panel-danger" id="total_nodes">
                  <div class="panel-heading">
                    <h1 class="panel-title text-center"><?=intval($avg_version)?></h1>
                  </div>
                  <div class="panel-body text-center">                        
                    <strong>Average Cjdns Protocol Version</strong>
                  </div>
                </div>
              </div>

              <div class="col-xs-12 col-sm-4">
                <div class="panel status panel-danger" id="total_nodes">
                  <div class="panel-heading">
                    <h1 class="panel-title text-center"><?=var_dump($avg_peers)?></h1>
                  </div>
                  <div class="panel-body text-center">                        
                    <strong>Average Cjdns Peers</strong>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <h4>People</h4>
            </div>
            <div class="panel-body">
              <ul class="text-left">
                <li>Human Directory</li>
                <li>PGP, Social Directory</li>
                <li>Add/Edit your profile</li>
              </ul>
              <p class="text-muted small">Coming Soon!</p>
            </div>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="panel panel-success text-center">
            <div class="panel-heading">
              <h4>Services</h4>
            </div>
            <div class="panel-body">
              <ul class="text-left">
                <li>Services Directory</li>
                <li>Public ircd's, httpd's, stmpd's</li>
                <li>Add/Edit your service</li>
              </ul>
              <p><a href="/services" class="btn btn-default">Services</a></p>
            </div>
          </div>
        </div>

      </div>
    </div>
    <?=$tmpl->getJs('basic')?>
  </body>
</html>
