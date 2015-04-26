<?php $this->layout('base::template', ['title' => 'Browse']); ?>
<?php $this->start('extra_css') ?>
<link href="/assets/css/node.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<?php $this->stop() ?>
<div class="jumbotron jumbotron-default text-center">
<h1>Nodes</h1>
<p class="lead">the hyperboria node directory.</p>
</div>
<div class="container" role="main">
<div class="col-xs-12 col-md-8 col-md-offset-2 browse">
<?php
 if($nodes !== false) { ?>
    <?= $nodes['pagination'] ?>
     <div class="bootcards-list">
        <div class="panel panel-default">               
            <div class="list-group">
<?php foreach($nodes['data'] as $n) { ?>
                <a class="list-group-item" href="/node/<?= $n['addr'] ?>">
                    <img src="/assets/avatars/<?= $this->e($n['avatar_url']) ?>" class="img-rounded pull-left">
                    <h4 class="list-group-item-heading"><?= $n['addr'] ?></h4>
                    <p class="list-group-item-text"><strong><?= $n['hostname'] ?></strong> <span class="label label-warning">v<?= $n['version'] ?></span> <span class="label label-primary"><?= $n['latency'] ?>ms</span> <span style="padding:0 10px;">Last Seen: <time class="timeago" datetime="<?= $n['last_seen'] ?>"><?= $n['last_seen'] ?></time></span></p>
                </a>
<?php } ?>
            </div>
        </div>
    </div>
    <?= $nodes['pagination'] ?>
<?php } ?>
</div>
</div>

<?php $this->start('extra_js') ?>
<script type="text/javascript">
$('.dropdown-toggle').dropdown();
$('.browse #node_addr').each(function() {
$(this).html($(this).html().substr(0, $(this).html().length-4)
+ "<span style='color: #F5AB35;'>"
+ $(this).html().substr(-4)
+ "</span>");
});
</script>
<?php $this->stop() ?>
