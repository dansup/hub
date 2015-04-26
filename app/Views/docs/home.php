<?php $this->layout('base::template', ['title' => 'Docs']); ?>
<?php $this->start('extra_css') ?>
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<?php $this->stop() ?>
<?php $this->start('extra_js') ?>
<script src="/assets/js/bootcards.js"></script>
<?php $this->stop() ?>

<div class="jumbotron jumbotron-default text-center">
<h1><i class=""></i> Docs</h1>
<p class="lead"></p>
</div>
<section class="container" id="main">
<div class="row">

<div class="col-xs-12 col-md-8 col-md-offset-2">
    <div class="panel panel-default bootcards-summary">
  <div class="panel-body">
    <div class="row">
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/help">
          <i class="fa fa-3x fa-life-ring"></i>
          <h4>Help</h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/faq">
          <i class="fa fa-3x fa-bookmark-o"></i>
          <h4>FAQ</h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/federation">
          <i class="fa fa-3x fa-exchange"></i>
          <h4>Federation Docs</h4>
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/api/v0">
          <i class="fa fa-3x fa-reorder"></i>
          <h4>v0 API Docs</h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/developers">
          <i class="fa fa-3x fa-connectdevelop"></i>
          <h4>Developers</h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/api/embed">
          <i class="fa fa-3x fa-list-alt"></i>
          <h4>Embed Widgets</h4>
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/contribute">
          <i class="fa fa-3x fa-group"></i>
          <h4>Contribute</h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/site/tos">
          <i class="fa fa-3x fa-file-text-o"></i>
          <h4>Privacy/TOS</h4>
        </a>
      </div>
      <div class="col-xs-6 col-sm-4">
        <a class="bootcards-summary-item" href="/docs/source">
          <i class="fa fa-3x fa-code"></i>
          <h4>Source</h4>
        </a>
      </div>
    </div>
  </div>
</div>

</div>

</div>
</section>