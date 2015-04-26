<?php $this->layout('base::template', ['title' => 'Profile']); ?>

<?php $this->start('extra_css') ?>
<link href="/assets/css/node.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/morris.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<?php $this->stop() ?>
<?php $this->start('extra_js') ?>
<script src="/assets/js/bootcards.js"></script>
<script src="/assets/js/raphael-min.js"></script>
<?php $this->stop() ?>

<section class="container">

<div class="page-header">
<h1>
<?= $username ?>
</h1>
</div>
<pre>
<?= var_dump($data)?>
</pre>
</section>