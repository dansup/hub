<?php $this->layout('base::template', ['title' => 'People Browse']); ?>
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
            <li><a href="/people">People</a></li>
            <li class="active"><a href="/people/browse">Browse</a></li>
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
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        <?php if($people !== false) { ?>
             <div class="bootcards-list">
                <div class="panel panel-default">               
                    <div class="list-group">
        <?php foreach($people['data'] as $p) { ?>
                          <a class="list-group-item" href="/people/u/@<?= $this->e($p['username']) ?>">
                            <img src="http://socialno.de/<?= $this->e($p['username']) ?>/avatar/64" class="img-rounded pull-left"/>
                            <h4 class="list-group-item-heading"><?= $this->e($p['username']) ?></h4>
                            <p class="list-group-item-text">Joined: <?= $this->e($p['date_created']) ?></p>
                          </a>
        <?php } ?>
                    </div>
                </div>
            </div>
            <?= $people['pagination'] ?>
        <?php } ?>


    </div>
</div>
</section>