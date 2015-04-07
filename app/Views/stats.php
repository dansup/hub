<?php $this->layout('template', ['title' => 'Network Stats']); ?>
<section id="main">
<div class="jumbotron jumbotron-default text-center">
<h2>Network Stats</h2>
<p>Data generated: <time class="timeago" datetime="<?= date('c') ?>"></time></p>
</div>
<div class="row content">
    <div class="row">
        <div class="col-xs-12 col-sm-4 well well-sm text-center">
            <h3><?= $this->e($total) ?> <br>Total Nodes</h3>
            <p class="text-muted small">Seen since May 2013.</p>
        </div>
        <div class="col-xs-12 col-sm-4 well well-sm text-center">
            <h3><?= $this->e($avg_lat) ?> <br>Average Latency (ms)</h3>
            <p class="text-muted small">From this server.</p>
        </div>
        <div class="col-xs-12 col-sm-4 well well-sm text-center">
            <h3><?= $this->e($avg_ver) ?> <br>Average Cjdns Version</h3>
            <p class="text-muted small">Current version: <?= CJDNS_LATEST ?></p>
        </div>
    </div>
    </div>
</section>