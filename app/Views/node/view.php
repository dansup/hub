<?php $this->layout('base::template', ['title' => 'View Node']); ?>


<div class="node-profile">
<div class="jumbotron node-header">

</div>
<div class="node-subheader">
<div class="avatar">
    <img class="img-thumbnail" src='/assets/avatars/<?= $this->e($node['addr'], 'sha1') ?>.png' width="164" height="164"/>
</div>
<div class="info">
<p class="ip"><?= $this->e($ip) ?></p>
<span id="peers">
<h4><strong><a href="/node/<?=$ip?>/peers"><?= $this->e($node_peers, 'count|prettyNull') ?></strong>
<p class="lead">peers</a></p></h4>
</span>
<span id="version">
<h4><strong><?= $this->e($node['version'], 'prettyNull') ?></strong></h4>
<p class="lead">Version</p>
</span>
<span id="latency">
<h4><strong><?= $this->e($node['latency'], 'prettyNull') ?> ms</strong></h4>
<p class="lead">latency</p>
</span>
</div>
</div>
</div>
<div class="container" role="main">
<div class="row">

<div class="col-xs-12 node-data">
<div class="text-center lead">
    <div class="node-hostname">
        <i class="fa fa-info-circle"></i> 
        Hostname :
    <?= $this->e($node['hostname'], 'prettyNull') ?> 
    </div>
    <div class="node-country">
        <i class="fa fa-map-marker"></i> 
        Location:
        <?= $this->e($node['country'], 'prettyNull') ?>
    </div>
</div>
<div class="text-center lead">
    <div class="node-hostname">
        <i class="fa fa-user"></i> 
        Operator :
    <?= $this->e($node['ownername'], 'prettyNull') ?> 
    </div>
    <div class="node-country">
        <i class="fa fa-globe"></i> 
        MeshLocal :
        None
    </div>
</div>
<?php if($node['lat'] !== null && $node['lng'] !== null): ?>
<div class="text-center lead">
    <div class="node-pubkey">
        <i class="fa fa-map-marker"></i> 
        Lat: <?= $this->e($node['lat'], 'prettyNull') ?>
        Lng: <?= $this->e($node['lng'], 'prettyNull') ?>
        <a class="btn btn-primary btn-sm" href="/maps/meshlocals?lat=<?= $this->e($node['lat'], 'prettyNull') ?>&amp;lng=<?= $this->e($node['lng'], 'prettyNull') ?>&amp;zoom=14">View on MeshMap</a>
    </div>
</div>
    <?php endif ?>
<div class="text-center lead">
    <div class="node-pubkey">
        <?= $this->e( $node['public_key'], 'prettyNull' ) ?>
        <p>
        <small><i class="fa fa-shield"></i> Public Key</small>
        </p>
    </div>
</div>
<hr>
</div>
<div class="col-xs-12 col-md-6 col-md-offset-3">

<div class="panel comments-widget panel-default">
<div class="panel-heading">Comments</div>
<div class="panel-body">
<?php if ($comments['count'] > 0): ?>
    <?php foreach( $comments['data'] as $c ): ?>
    <div class="comment">
    <img src="/assets/avatars/<?= $this->e( $c['author'], 'sha1' ) ?>.png" alt="" class="comment-avatar">
    <div class="comment-body">
        <div class="comment-text">
            <div class="comment-heading">
                <a href="/node/<?= $this->e( $c['author'] ) ?>" title="<?= $this->e( $c['author'] ) ?>"><?= $this->e( $c['author'] ) ?></a><span> <time class="timeago" datetime="<?= $this->e( $c['created'] ) ?>"><?= $this->e( $c['created'], 'prettyNull' ) ?></time></span>
            </div>
            <?= $this->e( $c['body'] ) ?>
        </div>
        <div class="comment-footer">
            <a href="#"><i class="fa fa-thumbs-o-up"></i></a>&nbsp;&nbsp;
            <a href="#"><i class="fa fa-thumbs-o-down"></i></a>
            &nbsp;&nbsp;·&nbsp;&nbsp;
            <a href="#">Reply</a>
        </div>
    </div> <!-- / .comment-body -->
    </div>
    <?php endforeach ?> 
<?= $comments['pagination'] ?>
<?php else: ?>
    <p class="text-center lead">no comments, be the first to post one, slick.</p>
<?php endif ?>  

</div>
<div class="panel-footer comments-form">
<form action="/comment/add" method="post">
<input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>" />
<input type="hidden" name="<?= $form_names['id'] ?>" value="<?= $ip ?>" />
<div class="input-cont">
<input class="form-control" type="text" name="<?= $form_names['body'] ?>" placeholder="Type a message here...">
</div>
<div class="btn-cont">
<span class="arrow">
</span>
<button type="submit" class="btn blue icn-only">
<i class="fa fa-check icon-white"></i>
</button>
</div>
</form>
</div>
</div>

<div>

</div>
</div>


</div>
</div>
