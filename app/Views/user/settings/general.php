<?php $this->layout('template', ['title' => 'User Settings']) ?>

<div class="jumbotron jumbotron-default text-center">
<h1><i class="fa fa-user"></i> User Settings</h1>
<p class="lead"></p>
</div>
<section class="container" id="main">
<div class="row">

<div class="col-xs-12 col-md-8 col-md-offset-3" style="padding-bottom:40px;">
  <ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="/user/settings">General Settings</a></li>
  <li role="presentation"><a href="/user/settings/api">Api Settings</a></li>
  <li role="presentation"><a href="/user/settings/node">Node Settings</a></li>
  <li role="presentation"><a href="/user/settings/notifications">Notification Settings</a></li>
  </ul>
</div>
<form class="form-horizontal">
<div class="col-xs-12 col-md-6">
  <div class="form-group well well-sm">
    <label for="inputUsername" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputUsername" placeholder="derp" aria-describedby="username-helpBlock" disabled>
      <span id="username-helpBlock" class="help-block">Set a unique username. <i class="text-danger">You must register an account first</i></span>
    </div>
  </div>
  <div class="form-group well well-sm">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" aria-describedby="profileState-helpBlock"disabled> Public Profile
        </label>
        <span id="profileState-helpBlock" class="help-block">Enable your user profile visibility to public.<i class="text-danger">You must register an account first</i></span>
      </div>
    </div>
  </div>
</div>
<div class="col-xs-12 col-md-6">
<div class="well">
<p class="lead">Register an account to gain access to social features.</p>
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
</section>