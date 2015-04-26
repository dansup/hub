<?php $this->layout('base::template', ['title' => 'MeshLocals Browse']); ?>
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
            <li><a href="/meshlocals">MeshLocals</a></li>
            <li class="active"><a href="/meshlocals/browse">Browse</a></li>
            <li><a href="/meshlocals/new">Create</a></li>
          </ul>
</nav>
<section class="container" id="main">
<div class="row">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        <?php if($meshlocal !== false) { ?>
             <div class="bootcards-list">
                <div class="panel panel-default">               
                    <div class="list-group">
        <?php foreach($meshlocal['data'] as $m) { ?>
                          <a class="list-group-item" href="/meshlocals/v/<?= $this->e($m['id']) ?>/<?= $this->e($m['url_slug']) ?>">
                            <h4 class="list-group-item-heading"><?= $this->e($m['name']) ?></h4>
                            <p class="list-group-item-text">Joined: <?= $this->e($m['created']) ?></p>
                          </a>
        <?php } ?>
                    </div>
                </div>
            </div>
            <?= $meshlocal['pagination'] ?>
        <?php } ?>


    </div>
</div>
</section>