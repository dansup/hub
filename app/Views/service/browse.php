<?php $this->layout('template', ['title' => 'Services']) ?>

<div class="jumbotron jumbotron-default text-center">
<h1>Services</h1>
<p class="lead">Active services on the network.</p>
</div>
<div class="container" role="main">
<div class="row">
<div class="col-xs-12">
<?= $service->getAll($page, $order_by) ?>
</div>
</div>
</div>

