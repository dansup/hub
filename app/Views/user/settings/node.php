<?php $this->layout('template', ['title' => 'User Settings']) ?>

<?php
  $map_unlisted = $map_listed = null;
  $_SESSION['clearnet'] = true;
  $map_privacy = $node['map_privacy'];
  switch ($map_privacy) {
    case 0:
      $map_unlisted = " checked=''";
      break;
    case 1:
      $map_listed = " checked=''";
      break;
    
    default:
      # code...
      break;
  }
?>
<div class="jumbotron jumbotron-default text-center">
<h1><i class="fa fa-user"></i> User Settings</h1>
<p class="lead"></p>
</div>
<section id="main">
<div class="container">
<div class="row">

<div class="col-xs-12 col-md-8 col-md-offset-3" style="padding-bottom:40px;">
  <ul class="nav nav-tabs">
  <li role="presentation"><a href="/user/settings">General Settings</a></li>
  <li role="presentation"><a href="/user/settings/api">Api Settings</a></li>
  <li role="presentation" class="active"><a href="/user/settings/node">Node Settings</a></li>
  <li role="presentation"><a href="/user/settings/notifications">Notification Settings</a></li>
  </ul>
</div>
<form class="form-horizontal"  method="post">
<input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>" />
<div class="col-xs-12 col-md-8 col-md-offset-2">
  <h2>General Settings</h2>
  <div class="form-group well well-sm">
    <label for="<?= $form_names['node_hostname'] ?>" class="col-sm-2 control-label">Hostname</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="<?= $form_names['node_hostname'] ?>" name="<?= $form_names['node_hostname'] ?>" placeholder="<?=$this->e($node['hostname'], 'prettyNull')?>" aria-describedby="hostname-helpBlock">
      <span id="hostname-helpBlock" class="help-block">A hostname is a label associated with a node, used primarily for human use. It should be unique.</span>
    </div>
  </div>
  <div class="form-group well well-sm">
    <label for="<?= $form_names['node_ownername'] ?>" class="col-sm-2 control-label">Ownername</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="<?= $form_names['node_ownername'] ?>" name="<?= $form_names['node_ownername'] ?>" placeholder="<?=$this->e($node['ownername'], 'prettyNull')?>" aria-describedby="ownername-helpBlock">
      <span id="ownername-helpBlock" class="help-block">Your nickname.</span>
    </div>
  </div>
</div>
<div class="col-xs-12 col-md-8 col-md-offset-2">
  <h2>Location Settings</h2>
  <p class="lead">All fields are optional, however they are required for MeshMap listings.</p>
    <div class="form-group well well-sm">
        <label for="<?= $form_names['node_map_privacy'] ?>" class="col-sm-2 control-label">Privacy</label>
      <div class="col-sm-10">
      <div class="radio">
                        <label>
                          <input type="radio" name="<?= $form_names['node_map_privacy'] ?>" value="0"<?=$map_unlisted?>>
                          Unlisted/Inactive
                        </label>
          </div>
          <div class="radio">
                        <label>
                          <input type="radio" name="<?= $form_names['node_map_privacy'] ?>" value="1"<?=$map_listed?>>
                          Listed
                        </label>
            </div>
        <span id="meshmapState-helpBlock" class="help-block">Enable your MeshMap public node listing.</span>
      </div>
  </div>
  <div class="form-group well well-sm">
    <label for="<?= $form_names['node_lat'] ?>" class="col-sm-2 control-label">Lat</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="<?= $form_names['node_lat'] ?>" name="<?= $form_names['node_lat'] ?>" placeholder="<?= $this->e($node['lat']) ?>" aria-describedby="lat-helpBlock">
      <span id="lat-helpBlock" class="help-block">Your nodes latitude.</span>
    </div>
  </div>
    <div class="form-group well well-sm">
    <label for="<?= $form_names['node_lng'] ?>" class="col-sm-2 control-label">Long</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="<?= $form_names['node_lng'] ?>" name="<?= $form_names['node_lng'] ?>" placeholder="<?= $this->e($node['lng']) ?>" aria-describedby="lat-helpBlock">
      <span id="lat-helpBlock" class="help-block">Your nodes longitude.</span>
    </div>
  </div>
</div>
<div class="col-xs-12">
  <div class="form-group">
    <div class="text-center">
      <button type="submit" class="btn btn-default">Save</button>
    </div>
  </div>
</div>
</form>

</div>
</div>
</section>