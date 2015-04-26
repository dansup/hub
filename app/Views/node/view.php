<?php $this->layout('base::template', ['title' => 'View Node']); ?>

<?php $this->start('extra_css') ?>
<link href="/assets/css/node.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/morris.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<?php $this->stop() ?>
<?php $this->start('extra_js') ?>
<script src="/assets/js/bootcards.js"></script>
<script src="/assets/js/raphael-min.js"></script>
<script src="/assets/js/morris.min.js" type="text/javascript"></script>
<script type="text/javascript">

var version = new Morris.Line({
    element: 'version-line-chart',
    resize: true,
    data: <?= $node_vgraph ?>,
    xkey: 'x',
    ykeys: 'y',
    labels: ['Version'],
    preUnits: 'v',
    parseTime: false,
    lineColors: ['#3c8dbc'],
    hideHover: 'auto'
});
var latency = new Morris.Line({
    element: 'latency-line-chart',
    resize: true,
    data: <?= $node_lgraph ?>,
    xkey: 'x',
    ykeys: 'y',
    labels: ['Latency'],
    postUnits: 'ms',
    parseTime: false,
    lineColors: ['#3c8dbc'],
    hideHover: 'auto'
});  
$('div#nm-tabs a[data-identifier="latency"]').on('shown.bs.tab', function (e) {
    latency.redraw();
});
$('div#nm-tabs a[data-identifier="version"]').on('shown.bs.tab', function (e) {
    version.redraw();
});
</script>
<?php $this->stop() ?>

<div class="node-profile">
    <div class="jumbotron node-header bkg-<?=$this->e($node['profile_header_img'])?>">
    </div>

    <div class="node-subheader">

        <div class="avatar">
            <img class="img-thumbnail" src='/assets/avatars/<?= $this->e($node['addr'], 'sha1') ?>.png' width="164" height="164"/>
        </div>

        <div class="info">
            <p class="ip"><?= $this->e($ip) ?></p>
            <p class="lead"><?= $this->e($node['hostname']) ?></p>
        </div>

    </div>
    <div class="node-datamenu">
    </div>
</div>
<div class="container" role="main">
    <div class=" node-body row">

        <div class="col-xs-12 col-md-4 node-data">
            <div class="text-center" style="padding-bottom:20px;">
                <div id="nm-tabs" class="btn-group" role="group" aria-label="Node Data">
                    <a class="btn btn-default" href="#comments" aria-controls="comments" role="tab" data-toggle="tab" data-identifier="comments"><strong><i class="fa fa-comments"></i></strong></a>
                    <a class="btn btn-default" href="#latency" aria-controls="latency" role="tab" data-toggle="tab" data-identifier="latency"><strong><?= ( $this->e($node['latency']) == null ) ? '? ms'  :  $this->e($node['latency']).' ms' ?></strong></a>            
                    <a class="btn btn-default" href="#version" aria-controls="version" role="tab" data-toggle="tab" data-identifier="version">v<strong><?= $this->e($node['version'], 'prettyNull') ?></strong></a>
                    <a class="btn btn-default" href="#peers" aria-controls="peers" role="tab" data-toggle="tab" data-identifier="peers"><strong><?= $this->e($node_peers, 'count|prettyNull') ?></strong> peers</a>
                    <!--  <a class="btn btn-primary" href="/api/v0/node/info/<?=$ip?>.json?pretty=true"><i class="fa fa-file-text-o"></i></a> -->
                    <a class="btn btn-warning" href="/api/v0/node/feed/<?=$ip?>.rss"><i class="fa fa-rss"></i></a>
                </div>
            </div>
            <div id="ni_widget">
                <div class="ni_header">
                    <p class="lead">NodeInfo</p>
                    <div class="ni-spacer"></div>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><i class="fa fa-circle-o"></i> First Seen: <time class="timeago" datetime="<?= $this->e($node['first_seen'], 'prettyNull') ?>"></time></li>
                    <li class="list-group-item"><i class="fa fa-circle"></i> Last Seen: <time class="timeago" datetime="<?= $this->e($node['last_seen'], 'prettyNull') ?>"></time></li>
                    <li class="list-group-item"><i class="fa fa-map-marker"></i> Location: <?= $this->e($node['country'], 'prettyNull') ?></li>
                    <li class="list-group-item"><i class="fa fa-user"></i> Operator: <?= $this->e($node['ownername'], 'prettyNull') ?> </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-md-8">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="comments">
                    <div class="panel comments-widget panel-default">
                        <div class="panel-heading comments-form">
                            <p class="lead text-center">
                                Activity Feed
                            </p>
                            <form action="/comment/node/add" method="post">
                                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>" />
                                <input type="hidden" name="<?= $form_names['id'] ?>" value="<?= $ip ?>" />
                                <div class="input-cont">
                                    <input class="form-control" type="text" name="<?= $form_names['body'] ?>" placeholder="Type a message here...">
                                </div>
                                <div class="btn-cont">
                                    <span class="arrow">
                                    </span>
                                    <button type="submit" class="btn blue">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="panel-body">
                            <?php if ($comments['count'] > 0): ?>
                            <?php foreach( $comments['data'] as $c ): ?>
                            <?php 
                            $username = ($this->e( $c['author_type'] ) == 'user') ? $this->e( $c['meta'], 'json_decode' ) : $this->e( $c['author'] );
                            $avatar = ($c['author_type'] == 'node') ? '/assets/avatars/'.sha1($username).'.png' : "/assets/avatars/unknown.png";
                            $user_url = ($c['author_type'] == 'node') ? '/node/'.$username : '/people/u/@'.$username;
                            ?>
                            <?php switch ($c['type']) { 
                                case 'node_comment': ?>
                                <div id= "<?= sha1($c['id'].$c['author']) ?>" class="comment">
                                    <img src="<?= $avatar ?>" alt="" class="comment-avatar">
                                    <div class="comment-body">
                                        <div class="comment-text">
                                            <div class="comment-heading">
                                                <a href="<?= $user_url ?>" title="<?= $username ?>"><?= $username ?></a>
                                                <span> <time class="timeago" datetime="<?= $this->e( $c['timestamp'] ) ?>"><?= $this->e( $c['timestamp'], 'prettyNull' ) ?></time></span>
                                            </div>
                                            <div style="word-wrap: break-word;"> <?= $this->e( $c['body'] ) ?></div>
                                        </div>
                                        <div class="comment-footer">
                                            <a href="#"><i class="fa fa-thumbs-o-up"></i></a>&nbsp;&nbsp;
                                            <a href="#"><i class="fa fa-thumbs-o-down"></i></a>
                                            &nbsp;&nbsp;·&nbsp;&nbsp;
                                            <a href="#">Reply</a>
                                        </div>
                                    </div> 
                                </div>
                                <?php break; ?>
                                <?php  case 'node_profile_update': ?>
                                <div id= "<?= sha1($c['id'].$username) ?>" class="comment">
                                    <img src="<?= $avatar ?>" alt="" class="comment-avatar">
                                    <div class="comment-body">
                                        <div class="comment-text">
                                            <div style="word-wrap: break-word;">User updated their profile.  <time class="timeago text-muted small" datetime="<?= $this->e( $c['timestamp'] ) ?>"><?= $this->e( $c['timestamp'], 'prettyNull' ) ?></time></div>
                                        </div>
                                    </div> 
                                </div>
                                <?php break; ?>

                                <?php  case 'created_meshlocal': ?>
                                <div id= "<?= sha1($c['id'].$c['author']) ?>" class="comment">
                                    <img src="/assets/avatars/<?= $this->e( $c['author'], 'sha1' ) ?>.png" alt="" class="comment-avatar">
                                    <div class="comment-body">
                                        <div class="comment-text">
                                            <div style="word-wrap: break-word;">User created a <a href="<?= $this->e( $c['identifier'] ) ?>">meshlocal</a>.  <time class="timeago text-muted small" datetime="<?= $this->e( $c['timestamp'] ) ?>"><?= $this->e( $c['timestamp'], 'prettyNull' ) ?></time></div>
                                        </div>
                                    </div> 
                                </div>
                                <?php break; ?>

                                <?php default: ?>
                                <?php break; } ?>
                            <?php endforeach ?> 
                            <?= $comments['pagination'] ?>
                        <?php else: ?>
                        <p class="text-center lead">no comments, be the first to post one, slick.</p>
                    <?php endif ?>  
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="latency">
            <div class="panel panel-default" id="node-stats-latency">
                <div class="panel-heading"><h3>Latency Graph</h3></div>
                <div class="panel-body">
                    <div class="chart-responsive">
                        <div class="chart" id="latency-line-chart"></div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="version">
            <div class="panel panel-default" id="node-stats-version">
                <div class="panel-heading"><h3>Version Graph</h3></div>
                <div class="panel-body">
                    <div class="chart-responsive">
                        <div class="chart" id="version-line-chart"></div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="peers">

            <div class="panel panel-default">
                <div class="panel-heading lead text-center">Peers</div>
                <div class="panel-body">
                    <?php if ($node_peers !== false): ?>
                    <div class="bootcards-list">
                        <div class="panel panel-default">               
                            <div class="list-group">
                                <?php foreach ($node_peers as $peer): ?>
                                <?php 
                                $url = ($peer['addr'] == null) ? $this->e($peer['peer_pubkey']) : $this->e($peer['addr']);
                                $identifier = ($peer['addr'] == null) ? substr($this->e($peer['peer_pubkey']), 0, 29).'...' : $this->e($peer['addr']);
                                $avatar = ($peer['addr'] == null) ? '/assets/avatars/unknown.png' : '/assets/avatars/'.sha1($identifier).'.png';
                                $hostname = ($peer['hostname'] == null) ? null : $this->e($peer['hostname']);
                                $version = ($peer['version'] == null) ? null : '<span class="label label-default"> v'.$this->e($peer['version']).'</span>';
                                ?> 
                                <a class="list-group-item" href="/node/<?= $identifier ?>">
                                    <img src="<?= $avatar ?>" class="img-rounded pull-left">
                                    <h4 class="list-group-item-heading"><?= $identifier ?></h4>
                                    <p class="list-group-item-text"><?= $hostname ?> <?= $version ?></p>
                                </a>
                            <?php endforeach ?>                   
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

</div>

<div>

</div>
</div>
</div>
</div>
