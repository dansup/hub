<?php $this->layout('template', ['title' => 'Notification Settings']) ?>
<section id="main" class="row">
<div class="container">

<div class="page-header">
    <h1 class="text-center">Notification Settings</h1>
    <p class="text-center text-danger">This page is not yet functional.</p>
</div>

<div class="col-xs-12 col-md-6 col-md-offset-3">
  <ul class="nav nav-tabs">
  <li role="presentation"><a href="/user/settings">General Settings</a></li>
  <li role="presentation"><a href="/user/settings/node">Node Settings</a></li>
  <li role="presentation" class="active"><a href="/user/settings/notifications">Notification Settings</a></li>
</ul>
<div style="padding-top:30px;"></div>
<form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputEmail" placeholder="examplehost.hype" aria-describedby="email-helpBlock">
      <span id="email-helpBlock" class="help-block"><span class="label label-default">OPTIONAL</span> A valid email address to recieve notifications.</span>
    </div>
  </div>

  <div class="form-group">
    <div class="text-center">
        <hr>
      <button type="submit" class="btn btn-default">Save</button>
    </div>
  </div>
</form>
</div>

</div>
</section>