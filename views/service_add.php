<?php
require('../inc/autoload.php');
require_once("/srv/http/hub.hyperboria/inc/login.lib.php");
$services = new Services();
$page = 'Services';
$m = null;
$csrf = new Csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
$resp = false;
// Generate Random Form Name
$form_names = $csrf->form_names(array('submit', 'name', 'uri', 'type', 'desc', 'adult'), false);

if(isset($_POST['submit']) && strlen($_POST[$form_names['name']]) > 2 )
{
  $name = filter_var($_POST[$form_names['name']]);
  $uri = filter_var($_POST[$form_names['uri']]);
  $type = filter_var($_POST[$form_names['type']]);
  $desc = filter_var($_POST[$form_names['desc']]);
  $adult = filter_var($_POST[$form_names['adult']]);
  if($csrf->check_valid('post')) {
    if($services->addNew($name, $uri, $type, $desc, $adult, $get_ip)) {
    header('Location: /services');
    exit();
    }
    else
    {
      $m = var_dump($csrf);
    }
  }
  else {
    throw new Exception('Invalid');
  }
  $form_names = $csrf->form_names(array('submit', 'name', 'uri', 'type', 'desc', 'adult'), true);
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
          <h1>Add Service</h1>
          <p class="lead">Add a new service listing.</p>
        </div>
      </div>
    </div>
<div class="container" role="main">
<div class="col-xs-12 col-md-8 col-md-offset-2 centered-pills" style="padding:40px 0;">
  <?php if($m !== null) { ?>
  <?=$m?>
  <?php } ?>
<ul class="nav nav-pills nav-pills-services">
  <li role="presentation"><a href="/services">All</a></li>
  <li role="presentation"><a href="/services/recent">Recently Added</a></li>
  <li role="presentation"><a href="/services/search">Search</a></li>
  <li role="presentation"><a href="/services/tags">Tags</a></li>
  <li role="presentation"><a href="/services/me">My Services</a></li>
  <li role="presentation" class="active"><a href="/services/add">Add New</a></li>
</ul>
<hr>
</div>
<div class="row">
<div class="col-xs-12 col-md-8 col-md-offset-2">
<div class="alert alert-block alert-success">
<p class="text-center lead">Use this form to submit a new service.</p>
<p class="text-center"><b>You must verify ownership by using the same ip address to add this service.</b></p>
</div>
<hr>

<form method="POST" class="form-horizontal">
<input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
  <div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="<?=$form_names['name'];?>" placeholder="Example">
      <p class="help-text text-muted small">The name of your service. Max 120 chars.</p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">URI</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="<?=$form_names['uri'];?>" placeholder="http://example.com">
      <p class="help-text text-muted small">The primary (valid) URI of your service. ICANN dns is assumed, if using another DNS please add information in the description.</p>
    </div>
  </div>
    <div class="form-group">
    <label class="col-sm-2 control-label">Type</label>
    <div class="col-sm-10">
      <select class="form-control" name="<?=$form_names['type'];?>">
        <option value="1">Website</option>
        <option value="2">IRCd</option>
        <option value="3">Mail Server</option>
        <option value="4">P2P</option>
        <option value="5">Other</option>
      </select>
	<p class="help-text text-muted small">If your service is not listed, please include a valid URI and information in the description.</p>
    </div>
  </div>
  <div class="form-group">
  	<label for="s_desc" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
	<textarea class="form-control" name="<?=$form_names['desc'];?>" rows="3"></textarea>
	<p class="help-text text-muted small">A short description of your service. Max 120 chars.</p>
	</div>
  </div>
        <div class="form-group">
    <label for="nsfw" class="col-sm-2 control-label">Adult Content</label>
    <div class="col-sm-10" id="<?=$form_names['adult'];?>">
      <select class="form-control" name="<?=$form_names['adult'];?>">
        <option value="0">No (SFW)</option>
        <option value="1">Yes (NSFW)</option>
      </select>
    </div>
</div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    	<hr>
      <button type="submit" name="submit" class="btn btn-success btn-lg center-block">Submit</button>
      <hr>
      <p class="help-text">By submitting this form, you agree to the <a href="/tou">Terms of Use</a> of this service.</p>
    </div>
  </div>
</form>
</div>

</div>


</div>
<?=$template->getJs('basic')?>
</body>
</html>
