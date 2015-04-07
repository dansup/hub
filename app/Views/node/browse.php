<?php $this->layout('base::template', ['title' => 'Browse']); ?>

<div class="jumbotron jumbotron-default text-center">
<h1>Nodes</h1>
<p class="lead">the hyperboria node directory.</p>
</div>
<div class="container" role="main">
<div class="col-xs-12 browse">
<?=$node->allKnownNodes($page, $order_by)?>
</div>
</div>

<?php $this->start('extra_js') ?>
<script type="text/javascript">
$('.dropdown-toggle').dropdown();
$('.browse #node_addr').each(function() {
$(this).html($(this).html().substr(0, $(this).html().length-4)
+ "<span style='color: #F9690E;'>"
+ $(this).html().substr(-4)
+ "</span>");
});
</script>
<?php $this->stop() ?>
