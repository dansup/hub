<?php $this->layout('base::template', ['title' => 'View Node']); ?>

<div class="promo promo-nodes" style="margin-top:-5px;height:240px;">
	<div class="container">
		<div class="text-center">
			<p class="lead node-name"><?= $this->e( $node['hostname'] ) ?></p>
			<p class="h2 node-address"><?= $this->e( $node['addr'] ) ?></p>
		</div>
	</div>
</div>
<div class="container" style="padding-top:40px" role="main">
	<div class="row">


		<div class="col-xs-12 col-md-6">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td><p  style="margin-top:10px;"><strong>owner:</strong> </p></td>
						<td><p  style="margin-top:10px;"><?= $this->e( $node['ownername'] ) ?></p></td>
					</tr>
					<tr>
						<td><p  style="margin-top:10px;"><strong>first seen</strong>: </p></td>
						<td><p  style="margin-top:10px;"><span><time class="timeago" datetime="<?= $this->e( $node['first_seen'] ) ?>"><?= $this->e( $node['first_seen'] ) ?></time></span></p></td>
					</tr>
					<tr>
						<td><p  style="margin-top:10px;"><strong>last seen</strong>: </p></td>
						<td><p  style="margin-top:10px;"><span><time class="timeago" datetime="<?= $this->e( $node['last_seen'] ) ?>"><?= $this->e( $node['last_seen'] ) ?></time></span></p></td>
					</tr>
					<tr>
						<td><p  style="margin-top:10px;"><strong>location:</strong> </p></td>
						<td><p  style="margin-top:10px;"><?= $this->e( $node['country'] ) ?></p></td>
					</tr>
					<tr>
						<td><p  style="margin-top:10px;"><strong>public key:</strong> </p></td>
						<td><p  style="margin-top:10px; font-size:13px;"><?= $this->e( $node['public_key'] ) ?></p></td>
					</tr>
					<tr>
						<td><p  style="margin-top:10px;"><strong>cjdns version:</strong> </p></td>
						<td><p  style="margin-top:10px;"><span class="label label-default"><?= $this->e( $node['cjdns_protocol'] ) ?></span></p></td>
					</tr>
				</tbody>
			</table>

			<div class="panel panel-default" id="node-stats">
				<div class="panel-heading"><h3>Latency Graph</h3></div>
				<div class="panel-body">
					<div class="chart-responsive">
						<div class="chart" id="line-chart"></div>
					</div>
				</div>
			</div>

		</div>
		<div class="col-xs-12 col-md-6" style="min-height:190px;padding-top:0px;">
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<div class="panel status panel-danger">
						<div class="panel-heading">
							<h1 class="panel-title text-center"><?= $this->e( count($node_peers) ) ?></h1>
						</div>
						<div class="panel-body text-center">                        
							<strong>Peers</strong>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div class="panel status panel-info">
						<div class="panel-heading">
							<h1 class="panel-title text-center"><?= $this->e( $node['latency'] ) ?><small>ms</small></h1>
						</div>
						<div class="panel-body text-center">                        
							<strong>Average Latency</strong>
						</div>
					</div>
				</div>
			</div>



		</div>

	</div>
</div>
<?php $this->start('extra_js') ?>
<script src="/assets/js/raphael-min.js"></script>
<script src="/assets/js/morris.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
});
$('.dropdown-toggle').dropdown();
$(function() {
	var line = new Morris.Line({
		element: 'line-chart',
		resize: true,
		data: <?= $lgraph ?>,
		xkey: 'x',
		ykeys: 'y',
		labels: ['Latency'],
		postUnits: 'ms',
		parseTime: false,
		lineColors: ['#3c8dbc'],
		hideHover: 'auto'
	});
});
</script>

<?php $this->stop() ?>