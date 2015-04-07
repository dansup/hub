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
                <h4><strong><a class="active"><?= $this->e($node_peers, 'count|prettyNull') ?></strong>
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
<div class="container" style="padding-top:40px" role="main">
    <div class="row">

        <div class="col-xs-12 ">
            <h4 class="text-center page-header"><?= $this->e($ip) ?></h4>
            <div class="text-center lead">
                <a class="btn btn-default" href="/node/<?= $this->e($ip) ?>">Back to Node Info</a>
            </div>
            <hr>
        </div>
        <div class="col-xs-12 col-md-6 col-md-offset-3">

            <div class="panel comments-widget panel-default">
                <div class="panel-heading">Peers</div>
                <div class="panel-body">
                    <?php if ($node_peers !== false): ?>
                                    <?php foreach ($node_peers as $peer): ?>
                                    <?php 
                                    $url = ($peer['addr'] == null) ? $this->e($peer['peer_pubkey']) : $this->e($peer['addr']);
                                    $identifier = ($peer['addr'] == null) ? '<span class="label label-default">PUBKEY</span> '.substr($this->e($peer['peer_pubkey']), 0, 29).'...' : $this->e($peer['addr']);
                                    ?> 
                                    <p class="lead"><a href="/node/<?= $url ?>"><?= $identifier ?></a></p>
                                <?php endforeach ?>                   
            <?php endif ?>
        </div>
    </div>
    <div>
    </div>
</div>
</div>
</div>
