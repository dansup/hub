<?php $this->layout('base::template', ['title' => 'View Service']); ?>

<?php

$deb = [
'name' => 'test',
'description' => 'sample desc',
'uri' => 'http://example.com',
'status' => 'Unverified',
'ip' => 'fc00',
'type' => '80 - Website',
'first_seen' => date('c')
];
?>

	<div class="jumbrotron">
	<div class="container">
	<div class="text-center">
		<h1><?= $this->e($service['name']) ?> </h1>
		<p class="lead"><?= $this->e($service['description']) ?></p>
	</div>
	</div>
	</div>
	<div class="container" role="main">
	<div class="col-xs-12 col-md-8 col-md-offset-2" style="padding:40px 0;">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<p class="lead"><strong>URI:</strong> <a href="<?= $this->e($service['uri']) ?>" rel="nofollow"><?= $this->e($service['uri']) ?></a></p>
				<p><strong>Description:</strong> <?= $this->e($service['description']) ?></p>
				<p><strong>Status:</strong> <?= $this->e((bool) $service['state']) ?></p>
			</div>

		</div>
	</div>
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-body"><br>
				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<td>
								<strong>IP:</strong> <span><?= $this->e($service['ip']) ?></span>
							</td>
						</tr>
						<tr>
							<td>
								<strong>First Seen:</strong> <span><time class="timeago" datetime="<?= $this->e($service['date_added']) ?>"><?= $this->e($service['date_added']) ?></time></span>
							</td>
						</tr>
						<tr> 
							<td>
								<strong>Service Type:</strong> <span><?= $this->e($service['type'], 'serviceType') ?></span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>
	</div>
	</div>

