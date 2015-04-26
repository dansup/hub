<?php $this->layout('base::template', ['title' => 'MeshLocals Map Home']); ?>

<?php $this->start('extra_css') ?>
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.css' rel='stylesheet' />
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.css' rel='stylesheet' />
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet' />
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
<link href="/assets/css/bootcards.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/assets/css/bootcards-desktop.css" rel="stylesheet" type="text/css" media="screen" />
<style>
   #map { position:absolute;height:100%; width:100%; }
</style>
<?php $this->stop() ?>

<div class="jumbotron jumbotron-default text-center">
<h1><i class="fa fa-map-marker"></i> MeshMap Map</h1>
<p class="lead">the global meshnet map</p>
</div>
<div id="main" style="min-height:500px;position:relative;">

<div id='map'></div>
</div>

<?php $this->start('extra_js') ?>
<script src='/assets/js/sha1.js'></script>
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.js'></script>
<!--[if lt IE 9]>
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.21.0/L.Control.Locate.ie.css' rel='stylesheet' />
<![endif]-->
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-hash/v0.2.1/leaflet-hash.js'></script>
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<script type="text/javascript"> 
        var map, newUser, users, firstLoad;
        firstLoad = true;
        L.mapbox.mapToken = <?= getenv('MESHLOCAL_MAP_STYLE') ?>;  
        L.mapbox.accessToken = <?= getenv('MESHLOCAL_MAP_TOKEN') ?>;
</script>
<script type="text/javascript" src="/assets/js/meshlocal.js"></script>
<script src="/assets/js/bootcards.js"></script>
<?php $this->stop() ?>