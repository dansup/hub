<?php $this->layout('base::template', ['title' => 'People Home']); ?>
<?php $this->start('extra_css') ?>
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<?php $this->stop() ?>
<?php $this->start('extra_js') ?>
<script src="/assets/js/bootcards.js"></script>
<?php $this->stop() ?>

<div class="jumbotron jumbotron-default text-center" style="margin-bottom:0;">
<h1><i class="fa fa-users"></i> People</h1>
<p class="lead"></p>
</div>
<nav>
          <ul class="nav nav-justified">
            <li class="active"><a href="/people">People</a></li>
            <li><a href="/people/browse">Browse</a></li>
            <li><a href="/people/gpg">GPG Keys</a></li>
            <?php if( !isset($_SESSION['logged_in']) OR $_SESSION['logged_in'] !== true ): ?>
            <li><a href="/auth/login">Login</a></li>
            <li><a href="/auth/register">Register</a></li>
            <li><a href="/auth/socialnode">Join/Login via Socialnode</a></li>
          <?php endif; ?>
          </ul>
</nav>
<section class="container" id="main">
<div class="row">

    <div class="col-xs-12 col-md-8 col-md-offset-2">
    <div class="jumbotron">
        <h2>The Human Directory</h2>
        <p class="lead">Browse, discover, follow your fellow peers.</p>
    </div>
    </div>
<div class="col-xs-12 col-md-8 col-md-offset-2">
    <div class="panel panel-default bootcards-summary">
  <div class="panel-body">
    <div class="row">
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/people/browse">
          <i class="fa fa-3x fa-users"></i>
          <h4>PeopleDB <span class="label label-primary">35</span></h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/people/map">
          <i class="fa fa-3x fa-map-marker"></i>
          <h4>People Map <span class="label label-primary">8</span></h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/people/groups">
          <i class="fa fa-3x fa-clipboard"></i>
          <h4>Groups <span class="label label-primary">4</span></h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/people/gpg">
          <i class="fa fa-3x fa-paw"></i>
          <h4>GPG <span class="label label-primary">65</span></h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/people/in/meshlocals">
          <i class="fa fa-3x fa-user-plus"></i>
          <h4>MeshLocals <span class="label label-primary">5</span></h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/people/seeking/peers">
          <i class="fa fa-3x fa-plus"></i>
          <h4>PeerFinder <span class="label label-primary">65</span></h4>
        </a>
      </div>
    </div>
  </div>
</div>

</div>

</div>
</section>