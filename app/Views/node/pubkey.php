<?php $this->layout('base::template', ['title' => 'Public Key']); ?>
<div class="jumbotron">
<h1>404 - Node Not Found.</h1>
<p>Identifer: <?= $this->e($key, 'prettyNull') ?></p>
<p>We're sorry, we cannot locate any information on this node.</p>
</div>