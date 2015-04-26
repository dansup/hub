<?php $this->layout('base::template', ['title' => 'MeshLocals']); ?>

<?php $this->start('extra_css') ?>
<link href="/assets/css/node.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<?php $this->stop() ?>
<?php $this->start('extra_js') ?>
<script src="/assets/js/bootcards.js"></script>
</script>
<?php $this->stop() ?>

<?php

$city = ($data['city'] !== null) ? $this->e($data['city']) : false; 
$country = ($data['country'] !== null) ? $this->e($data['country']) : false; 
$location = ($city && $country) ? $city.' ,'.$country : 'Undefined';
?>
<div class="node-profile">
    <div class="jumbotron node-header">
    </div>

    <div class="node-subheader">

        <div class="avatar">
            <img class="img-thumbnail" src='/assets/avatars/unknown.png' width="164" height="164"/>
        </div>

        <div class="info">
            <p class="ip"><?= $this->e($data['name'])?></p>
            <p class="lead">Location: <?= $location ?></p>
        </div>

    </div>
    <div class="node-datamenu">
    </div>
</div>
<div class="container" role="main">
    <div class=" node-body row">

        <div class="col-xs-12 col-md-4 node-data">
            <div class="text-center" style="padding-bottom:20px;">
                <div id="nm-tabs" class="btn-group" role="group" aria-label="Meshlocal Data">
                    <a class="btn btn-default" href="#comments" aria-controls="comments" role="tab" data-toggle="tab" data-identifier="comments"><strong><i class="fa fa-comments"></i></strong></a>
                    <a class="btn btn-primary" href="/api/v0/meshlocal/info/<?=$data['id']?>.json?pretty=true"><i class="fa fa-file-text-o"></i></a>
                    <a class="btn btn-warning" href="/api/v0/meshlocal/feed/<?=$data['id']?>.rss"><i class="fa fa-rss"></i></a>
                </div>
            </div>
            <div id="ni_widget">
                <div class="ni_header">
                    <p class="lead">MeshLocal Info</p>
                    <div class="ni-spacer"></div>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><i class="fa fa-circle-o"></i> Created: <time class="timeago" datetime="<?= $data['created'] ?>"><?= $data['created'] ?></time></li>
                    <li class="list-group-item"><i class="fa fa-map-marker"></i> ID : <?= $data['id'] ?></li>
                    <li class="list-group-item"><i class="fa fa-circle"></i> URL :  <a href="/ml/<?= $data['id'] ?>">hub.hyperboria.net/ml/<?= $data['id'] ?></a></li>
                    <li class="list-group-item"><i class="fa fa-user"></i> Operator :  <a href="/node/<?= $data['admin_ip'] ?>"><?= $data['admin_ip'] ?></a></li>
                </ul>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Bio</h3>
                <?php if($_SERVER['REMOTE_ADDR'] == $data['admin_ip']): ?>
                  <a class="btn btn-danger pull-right" href="/meshlocals/e/<?=$data['id']?>">
                    <i class="fa fa-pencil"></i>
                    Edit
                  </a>
              <?php endif; ?>
              </div>
              <div class="panel-body">
                <p><?= $this->e($data['bio']) ?></p>
              </div>
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
                            <form action="/comment/meshlocal/add" method="post">
                                <input type="hidden" name="<?= $token_id ?>" value="<?= $token_value ?>" />
                                <input type="hidden" name="<?= $form_names['id'] ?>" value="<?= $data['id'] ?>" />
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
                            <?php foreach( $comments['data'] as $c ): 
                            $username = ($this->e( $c['author_type'] ) == 'user') ? $this->e( $c['meta'], 'json_decode' ) : $this->e( $c['author'] );
                            $avatar = ($c['author_type'] == 'node') ? '/assets/avatars/'.sha1($username).'.png' : "/assets/avatars/unknown.png";
                            $user_url = ($c['author_type'] == 'node') ? '/node/'.$username : '/people/u/@'.$username;
                            ?>
                            <?php switch ($c['type']) { 
                                case 'meshlocal_comment': ?>
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

                                <?php default: continue; ?>
                                <?php break; } ?>
                            <?php endforeach ?> 
                            <?= $comments['pagination'] ?>
                        <?php else: ?>
                        <p class="text-center lead">no comments, be the first to post one, slick.</p>
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
