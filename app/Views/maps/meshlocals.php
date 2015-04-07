<?php $this->layout('base::template', ['title' => 'MeshLocals Map Home']); ?>

<?php $this->start('extra_css') ?>
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.css' rel='stylesheet' />
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
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.css' rel='stylesheet' />
<!--[if lt IE 9]>
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.21.0/L.Control.Locate.ie.css' rel='stylesheet' />
<![endif]-->
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-hash/v0.2.1/leaflet-hash.js'></script>
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/Leaflet.fullscreen.min.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.4/leaflet.fullscreen.css' rel='stylesheet' />
<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
<script type="text/javascript"> 
     var map, newUser, users, firstLoad;

      firstLoad = true;
        L.mapbox.accessToken = '<?= MAPBOX_KEY ?>';
        // Replace 'examples.map-i87786ca' with your map id.
        var mapboxTiles = L.tileLayer('https://{s}.tiles.mapbox.com/v4/<?= MAPBOX_ID ?>/{z}/{x}/{y}.png?access_token=' + L.mapbox.accessToken, {
        attribution: '<a href="http://www.mapbox.com/about/maps/" target="_blank">Terms &amp; Feedback</a>'
        });
      users = new L.FeatureGroup();
      users = new L.MarkerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
      newUser = new L.LayerGroup();


      map = new L.Map('map', {
        center: new L.LatLng(39.90, -93.69),
        zoom:5,
        maxZoom: 16,
        minZoom: 3,
        layers: [mapboxTiles, users, newUser]
      })
       .addControl(L.mapbox.geocoderControl('mapbox.places',{
        autocomplete: true
        }));


      $(document).ready(function() {
        $.ajaxSetup({cache:true});
        getUsers();
      });
      function getUrlParameter(sParam)
      {
          var sPageURL = window.location.search.substring(1);
          var sURLVariables = sPageURL.split('&');
          for (var i = 0; i < sURLVariables.length; i++) 
          {
              var sParameterName = sURLVariables[i].split('=');
              if (sParameterName[0] == sParam) 
              {
                  return sParameterName[1];
              }
          }
      }
       function geoLocate() {
        map.locate({setView: true, maxZoom: 17});
      }
      function initRegistration() {
        map.addEventListener('click', onMapClick);
        $('#map').css('cursor', 'crosshair');
        return false;
      }

      function cancelRegistration() {
        newUser.clearLayers();
        $('#map').css('cursor', '');
        map.removeEventListener('click', onMapClick);
      }

      function getUsers() {
        $.getJSON("/api/v0/meshmap/nodes.json", function (data){
        for (var i = 0; i < data.length; i++) {
            var location = new L.LatLng(data[i].lat, data[i].lng);
            var hostname = data[i].hostname;
            var addr = data[i].cjdns_ip;
            var contact = data[i].owner;
            var nodetype = data[i].node_type;
            var alivesince = data[i].registration_date;
            var peercount = data[i].peers;
            var activityfeedcount = data[i].afc;
            if(nodetype==="Undefined")
            {
              node_type = "<span class='label label-warning'>Unknown node type</span>";
            }
            else
              node_type = "<h4><span class='label label-primary'>"+ nodetype +"</span></h4>";
            //Node status, if inactive do not display. Need to finalize schema with the duos
            //var status = data[i].status;
            //if (data[i].status.length > 0) 
            
            var marker = new L.Marker(location, {
              title: name
            });
            marker.bindPopup("<div class='panel panel-primary'><div class='panel-heading'><h3 class='panel-title'>"+ hostname +"</h3></div><div class='panel-body'><div class='row'><div class='col-sm-12'><p><strong>"+ addr +"</strong></p><table class='table table-condensed table-bordered table-striped table-user-information'><tbody><tr><td colspan='2'><p class='text-center'>"+ nodetype +"</p></td></tr><tr><td>Owner:</td><td><b><i class='fa fa-user'></i> "+ contact +"</b></td></tr><tr><td>Registered since:</td><td><b>"+ alivesince +"</b></td></tr></tbody></table></div></div></div><div class='panel-footer'><button class='btn btn-primary' type='button' data-toggle='tooltip' data-original-title='Send message to user'><i class='fa fa-envelope'></i></button><span class='pull-right'><a class='btn btn-default' href='/node/"+ addr +"'><i class='fa fa-info-circle'></i></a></span></div></div>", {maxWidth: '400'});
            users.addLayer(marker);
          }
          }).complete(function() {
          if (firstLoad == true) {
            map.fitBounds(users.getBounds());
            firstLoad = false;
            var hash = L.hash(map);
          };
        });
      }
        L.control.locate().addTo(map);
        L.control.fullscreen().addTo(map);
       </script>

<?php $this->stop() ?>