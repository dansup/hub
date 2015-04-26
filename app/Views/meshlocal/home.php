<?php $this->layout('base::template', ['title' => 'Meshlocals Home']); ?>
<?php $this->start('extra_css') ?>
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<?php $this->stop() ?>
<?php $this->start('extra_js') ?>
<script src="/assets/js/bootcards.js"></script>
<?php $this->stop() ?>

<div class="jumbotron jumbotron-default text-center" style="margin-bottom:0;">
<h1><i class="fa fa-connectdevelop"></i> MeshLocals</h1>
<p class="lead">Community mesh networks</p>
</div>
<nav>
          <ul class="nav nav-justified">
            <li class="active"><a href="/meshlocals">MeshLocals</a></li>
            <li><a href="/meshlocals/browse">Browse</a></li>
            <li><a href="/meshlocals/new">Create</a></li>
          </ul>
</nav>
<section id="main">
<div class="container">

<div class="jumbotron" style="text-align: center;background-color: transparent;">
        <h1>Discover your meshlocal</h1>
        <p class="lead">A MeshLocal is a group of people interested in building a community-based wireless network with Cjdns running atop. The network is intended to form a part of Hyperboria. Currently, a MeshLocal connects with other MeshLocals over the internet, but this will eventually be phased out as physical, longer-distance links are created.</p>
        <p><a class="btn btn-lg btn-success" href="/meshlocals/new" role="button"><i class="fa fa-plus"></i> Register a meshlocal</a></p>
      </div>

      <? /*<div class="col-xs-12 col-md-8 col-md-offset-2">
            <div class="panel panel-default bootcards-summary">
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-6 col-sm-4">
                  <a class="bootcards-summary-item" href="/meshlocals/browse">
                    <i class="fa fa-3x fa-users"></i>
                    <h4>MeshLocals <span class="label label-primary">35</span></h4>
                  </a>
                </div>
                <div class="col-xs-6 col-sm-4">
                  <a class="bootcards-summary-item" href="/meshlocals/tools">
                    <i class="fa fa-3x fa-cogs"></i>
                    <h4>Tools <span class="label label-primary">8</span></h4>
                  </a>
                </div>
                <div class="col-xs-6 col-sm-4">
                  <a class="bootcards-summary-item" href="/people/groups">
                    <i class="fa fa-3x fa-clipboard"></i>
                    <h4>Groups <span class="label label-primary">4</span></h4>
                  </a>
                </div>
              </div>
            </div>
          </div>
      </div>
      */ ?>


</div>
</section>